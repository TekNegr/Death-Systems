import os
from typing import List
from transformers import GPT2LMHeadModel, GPT2Tokenizer, DataCollatorForLanguageModeling, Trainer, TrainingArguments
from datasets import load_dataset
import logging

class SimpleLanguageModel:
    def __init__(self, model_name: str = "gpt2", model_dir: str = "./model", identity_file: str = "identity.txt"):
        self.model_name = model_name
        self.model_dir = model_dir
        base_dir = os.path.dirname(os.path.abspath(__file__))
        self.identity_file = os.path.abspath(os.path.join(base_dir, identity_file))
        self.tokenizer = GPT2Tokenizer.from_pretrained(model_name)
        if self.tokenizer.pad_token is None:
            self.tokenizer.add_special_tokens({'pad_token': '[PAD]'})
        self.pretrained_model = GPT2LMHeadModel.from_pretrained(model_name)
        self.pretrained_model.resize_token_embeddings(len(self.tokenizer))
        self.trained_model = None
        self.model = self.pretrained_model
        # Attempt to load existing trained model if present
        trained_model_path = os.path.abspath(os.path.join(base_dir, "trained_model"))
        logging.info(f"Looking for trained model at {trained_model_path}")
        if os.path.exists(trained_model_path):
            try:
                self.trained_model = GPT2LMHeadModel.from_pretrained(trained_model_path)
                self.trained_model.resize_token_embeddings(len(self.tokenizer))
                self.model = self.trained_model
                logging.info(f"Loaded existing trained model from {trained_model_path}")
            except Exception as e:
                logging.error(f"Failed to load existing trained model: {e}")
        else:
            logging.info("No existing trained model found. Using pretrained model.")
            
            
    def _read_identity(self) -> str:
        if os.path.exists(self.identity_file):
            with open(self.identity_file, "r", encoding="utf-8") as f:
                return f.read().strip()
        else:
            return "You are death_instance_v0.1, a helpful AI assistant."

    def train(self, train_file: str, output_dir: str = None, epochs: int = 3, batch_size: int = 4):
        import os
        if output_dir is None:
            output_dir = "trained_model"
        base_dir = os.path.dirname(os.path.abspath(__file__))
        if os.path.isabs(output_dir):
            abs_output_dir = output_dir
        else:
            if output_dir.startswith("scripts/"):
                output_dir = output_dir[len("scripts/"):]
            abs_output_dir = os.path.abspath(os.path.join(base_dir, output_dir))

        print(f"Saving model to absolute path: {abs_output_dir}")

        dataset = load_dataset("text", data_files={"train": train_file})
        
        def tokenize_function(examples):
            return self.tokenizer(examples["text"], truncation=True, max_length=128, padding="max_length")

        tokenized_datasets = dataset.map(tokenize_function, batched=True, remove_columns=["text"])

        data_collator = DataCollatorForLanguageModeling(
            tokenizer=self.tokenizer, mlm=False,
        )

        if os.path.exists(abs_output_dir):
            print(f"Loading existing model from {abs_output_dir} for continued training.")
            model_to_train = GPT2LMHeadModel.from_pretrained(abs_output_dir)
            model_to_train.resize_token_embeddings(len(self.tokenizer))
        else:
            model_to_train = self.pretrained_model

        training_args = TrainingArguments(
            output_dir=abs_output_dir,
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
        os.makedirs(abs_output_dir, exist_ok=True)
        trainer.save_model(abs_output_dir)
        self.trained_model = GPT2LMHeadModel.from_pretrained(abs_output_dir)
        print(f"Model saved to: {abs_output_dir}")

    def selftrain(self, training_dialogs: List[str], output_dir: str = None, epochs: int = 3, batch_size: int = 4):
        filtered_dialogs = [dialog for dialog in training_dialogs if dialog.strip()]
        if not filtered_dialogs:
            raise ValueError("Training dialogs list is empty or contains only empty strings. Cannot train on empty data.")

        identity_prefix = self._read_identity() + "\\n"
        with open("temp_training_data.txt", "w", encoding="utf-8") as f:
            f.write(identity_prefix)
            for dialog in filtered_dialogs:
                dialog = dialog.strip()
                if not dialog.endswith("\\n"):
                    dialog += "\\n"
                f.write(dialog)

        self.train("temp_training_data.txt", output_dir=output_dir, epochs=epochs, batch_size=batch_size)

    def use_trained_model(self):
        if self.trained_model is not None:
            self.model = self.trained_model
        else:
            raise ValueError("No trained model available. Please train the model first.")

    def use_pretrained_model(self):
        self.model = self.pretrained_model

    def generate_text(self, full_prompt: str, max_length: int = 100) -> str:
        inputs = self.tokenizer(full_prompt, return_tensors="pt", padding=True)
        input_ids = inputs["input_ids"]
        attention_mask = inputs["attention_mask"]
        outputs = self.model.generate(
            input_ids=input_ids,
            attention_mask=attention_mask,
            max_length=max_length,
            num_return_sequences=1,
            temperature=0.7,
            top_k=50,
            top_p=0.95,
            no_repeat_ngram_size=2,
            do_sample=True,
            eos_token_id=self.tokenizer.eos_token_id,
            pad_token_id=self.tokenizer.pad_token_id,
        )
        generated_text = self.tokenizer.decode(outputs[0], skip_special_tokens=True)
        logging.info(f"Generated text: {generated_text}")
        response = generated_text[len(full_prompt):].strip()
        for stop_token in ["User:", "AI:"]:
            idx = response.find(stop_token)
            if idx != -1:
                response = response[:idx].strip()
        return response
