from fastapi import FastAPI, Request
from pydantic import BaseModel
from transformers import pipeline
from transformers import GPT2LMHeadModel, GPT2Tokenizer, TextDataset, DataCollatorForLanguageModeling, Trainer, TrainingArguments
import mysql.connector
import os
import torch

class Prompt(BaseModel):
    message: str

app = FastAPI()



# Charger le modèle GPT-2 (ou autre)
generator = pipeline("text-generation", model="gpt2")

def load_dataset(file_path, tokenizer, block_size=128):
    try:
        # Fetch training data from the database
        connection = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
            database="death_db"
        )
        cursor = connection.cursor()
        cursor.execute("SELECT user_input, ai_response FROM training_dialogs")
        rows = cursor.fetchall()
        cursor.close()
        connection.close()

        # Format the data and save it to a text file
        training_data = [f"User: {row[0]}\nAI: {row[1]}\n" for row in rows]
        with open(file_path, "w", encoding="utf-8") as file:
            file.writelines(training_data)

        print(f"Fetched {len(training_data)} rows from the database.")
        print(f"Training data saved to {file_path}")

        # Return a TextDataset
        return TextDataset(
            tokenizer=tokenizer,
            file_path=file_path,
            block_size=block_size
        )
    except Exception as e:
        print(f"An error occurred while fetching data: {e}")
        return None

def train_model(dataset: TextDataset, output_dir=None):
    
    try:
        data_collator = DataCollatorForLanguageModeling(tokenizer=tokenizer, mlm=False)

        training_args = TrainingArguments(
            output_dir=output_dir,
            overwrite_output_dir=True,
            num_train_epochs=3,
            per_device_train_batch_size=4,
            save_steps=10_000,
            save_total_limit=2,
            prediction_loss_only=True,
            logging_dir=os.path.join(output_dir, "logs"),
        )
        print("Training arguments set.")
        
        trainer = Trainer(
            model=model,
            args=training_args,
            data_collator=data_collator,
            train_dataset=dataset,
        )
        print("Trainer initialized.")
        
        trainer.train()
        
        return trainer
    except Exception as e:
        print(f"An error occurred during training: {e}")

def save_model(trainer, output_dir):
    try:
        trainer.save_model(output_dir)
        tokenizer.save_pretrained(output_dir)
        print(f"Model saved to {output_dir}")
    except Exception as e:
        print(f"An error occurred while saving the model: {e}")

def load_model(output_dir):
    try:
        model = GPT2LMHeadModel.from_pretrained(output_dir)
        tokenizer = GPT2Tokenizer.from_pretrained(output_dir)
        print(f"Model loaded from {output_dir}")
        dataset = load_dataset(output_dir, tokenizer)
        if dataset is None:
            print("Dataset is empty or invalid. Exiting.")
            return
        print(f"Dataset loaded with {len(dataset)} samples.")
        
        if dataset is not None:
            # Step 2: Train the model
            trainer = train_model(dataset, tokenizer, model)

            if trainer is not None:
                # Step 3: Save the model
                save_model(trainer, tokenizer)
                return
    except Exception as e:
        print(f"An error occurred while loading the model: {e}")
        

@app.post("/chat")
def chat(prompt: Prompt):
    output = generator(prompt.message, max_length=100, num_return_sequences=1)
    return {"response": output[0]["generated_text"]}

if __name__ == "__main__":
    import sys

    if len(sys.argv) > 2 and sys.argv[1] == "load_model":
        output_dir = sys.argv[2]

        # Initialize the model and tokenizer if the directory is empty
        if not os.path.exists(os.path.join(output_dir, "pytorch_model.bin")):
            print(f"No model found in {output_dir}. Initializing...")
            initialize_model(output_dir)

        # Load and train the model
        load_model(output_dir)
    else:
        print("No valid command provided. Use 'load_model <output_dir>' to load and train the model.")