from fastapi import FastAPI, Request
from pydantic import BaseModel
from transformers import pipeline

app = FastAPI()

# Charger le modèle GPT-2 (ou autre)
generator = pipeline("text-generation", model="gpt2")

class Prompt(BaseModel):
    message: str

@app.post("/chat")
def chat(prompt: Prompt):
    output = generator(prompt.message, max_length=100, num_return_sequences=1)
    return {"response": output[0]["generated_text"]}
