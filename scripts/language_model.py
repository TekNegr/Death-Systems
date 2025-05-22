import os
from typing import List
from transformers import GPT2LMHeadModel, GPT2Tokenizer, DataCollatorForLanguageModeling, Trainer, TrainingArguments
from datasets import load_dataset

class SimpleLanguageModel:
    def __init__(self, model_name: str = "gpt2", model_dir: str = "./model"):
        self.model_name = model_name
        self.model_dir = model_dir
        self.tokenizer = GPT2Tokenizer.from_pretrained(model_name)
        # Add pad token if not present
        if self.tokenizer.pad_token is None:
            self.tokenizer.add_special_tokens({'pad_token': '[PAD]'})
        self.pretrained_model = GPT2LMHeadModel.from_pretrained(model_name)
        # Resize model embeddings to accommodate new tokens
        self.pretrained_model.resize_token_embeddings(len(self.tokenizer))
        self.trained_model = None
        self.model = self.pretrained_model  # Current model in use

    def train(self, train_file: str, output_dir: str = None, epochs: int = 3, batch_size: int = 4):
        import os
        if output_dir is None:
            output_dir = "./scripts/trained_model"
        # Convert output_dir to absolute path based on this file's directory
        base_dir = os.path.dirname(os.path.abspath(__file__))
        output_dir = os.path.abspath(os.path.join(base_dir, output_dir)) if not os.path.isabs(output_dir) else output_dir

        print(f"Saving model to absolute path: {output_dir}")

        # Load dataset using Huggingface Datasets library
        dataset = load_dataset("text", data_files={"train": train_file})
        
        def tokenize_function(examples):
            return self.tokenizer(examples["text"], truncation=True, max_length=128)

        tokenized_datasets = dataset.map(tokenize_function, batched=True, remove_columns=["text"])

        data_collator = DataCollatorForLanguageModeling(
            tokenizer=self.tokenizer, mlm=False,
        )

        # Load existing model if available to continue training
        if os.path.exists(output_dir):
            print(f"Loading existing model from {output_dir} for continued training.")
            model_to_train = GPT2LMHeadModel.from_pretrained(output_dir)
            model_to_train.resize_token_embeddings(len(self.tokenizer))
        else:
            model_to_train = self.pretrained_model

        training_args = TrainingArguments(
            output_dir=output_dir,
            overwrite_output_dir=True,
            num_train_epochs=epochs,
            per_device_train_batch_size=batch_size,
            save_steps=10_000,
            save_total_limit=2,
            logging_dir='./logs',
            logging_steps=500,
        )

        trainer = Trainer(
            model=model_to_train,
            args=training_args,
            data_collator=data_collator,
            train_dataset=tokenized_datasets["train"],
        )

        trainer.train()
        # Ensure output directory exists before saving
        os.makedirs(output_dir, exist_ok=True)
        trainer.save_model(output_dir)
        self.trained_model = GPT2LMHeadModel.from_pretrained(output_dir)
        print(f"Model saved to: {output_dir}")
        
    def use_trained_model(self):
        if self.trained_model is not None:
            self.model = self.trained_model
        else:
            raise ValueError("No trained model available. Please train the model first.")

    def use_pretrained_model(self):
        self.model = self.pretrained_model

    def generate_text(self, prompt: str, max_length: int = 100) -> str:
        inputs = self.tokenizer.encode(prompt, return_tensors="pt")
        outputs = self.model.generate(inputs, max_length=max_length, num_return_sequences=1)
        return self.tokenizer.decode(outputs[0], skip_special_tokens=True)

    def selftrain(self, training_dialogs: List[str], output_dir: str = None, epochs: int = 3, batch_size: int = 4):
        """
        Train the model on all records of TrainingDialog resource.
        :param training_dialogs: List of dialog strings to train on.
        """
        # Filter out empty or whitespace-only dialogs
        filtered_dialogs = [dialog for dialog in training_dialogs if dialog.strip()]
        if not filtered_dialogs:
            raise ValueError("Training dialogs list is empty or contains only empty strings. Cannot train on empty data.")

        # Keep prefixes but ensure proper formatting with newlines between dialogs
        formatted_dialogs = []
        for dialog in filtered_dialogs:
            dialog = dialog.strip()
            # Ensure each dialog ends with a newline for separation
            if not dialog.endswith("\\n"):
                dialog += "\\n"
            formatted_dialogs.append(dialog)

        # Write formatted dialogs to a temporary training file
        temp_train_file = "temp_training_data.txt"
        with open(temp_train_file, "w", encoding="utf-8") as f:
            for dialog in formatted_dialogs:
                f.write(dialog)

        self.train(temp_train_file, output_dir=output_dir, epochs=epochs, batch_size=batch_size)

        # Remove temporary file
        # os.remove(temp_train_file)

    def interactivetrain(self, prompt: str, user_rating_callback, max_length: int = 100):
        """
        Interactive training session where user asks something, AI answers, user rates accuracy.
        The dialog is stored via user_rating_callback.
        :param prompt: User input prompt.
        :param user_rating_callback: Function to call with (prompt, response, rating).
        :param max_length: Max length of generated response.
        :return: AI generated response.
        """
        response = self.generate_text(prompt, max_length=max_length)
        print(f"AI response: {response}")
        rating = user_rating_callback(prompt, response)
        # Store dialog with rating (user_rating_callback should handle storage)
        return response, rating

if __name__ == "__main__":
    # Example usage
    lm = SimpleLanguageModel()
    # To train: lm.train("path_to_training_text.txt")
    # To switch to trained model: lm.use_trained_model()
    # To switch back to pretrained model: lm.use_pretrained_model()
    # To generate text:
    prompt = "Dear Hiring Manager,"
    print(lm.generate_text(prompt))
