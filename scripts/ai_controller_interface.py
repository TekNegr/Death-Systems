from fastapi import FastAPI
from pydantic import BaseModel
from typing import List
from AI_selector import select_emphasis
from language_model import SimpleLanguageModel
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

# Allow CORS for Laravel frontend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

lm = SimpleLanguageModel()

class TextEmbedding(BaseModel):
    text: str

class EmphasisRequest(BaseModel):
    job_title: str
    job_description: str
    skills: List[str]
    experiences: List[str]
    formations: List[str]

class GenerateTextRequest(BaseModel):
    prompt: str
    max_length: int = 100

class SelfTrainRequest(BaseModel):
    training_dialogs: List[str]
    epochs: int = 3
    batch_size: int = 4

class InteractiveTrainRequest(BaseModel):
    prompt: str
    rating: int

@app.get("/test-connection")
def test_connection():
    return {"message": "Python-Interface connected!"}

@app.post("/select-emphasis")
def select_emphasis_endpoint(request: EmphasisRequest):
    result = select_emphasis(
        job_title=request.job_title,
        job_description=request.job_description,
        skills=request.skills,
        experiences=request.experiences,
        formations=request.formations
    )
    return result

@app.post("/generate-text")
def generate_text_endpoint(request: GenerateTextRequest):
    text = lm.generate_text(request.prompt, max_length=request.max_length)
    return {"generated_text": text}

@app.post("/self-train")
def self_train_endpoint(request: SelfTrainRequest):
    try:
        print(f"Received training dialogs count: {len(request.training_dialogs)}")
        if len(request.training_dialogs) > 0:
            print(f"Sample training dialog: {request.training_dialogs[0]}")
        if not request.training_dialogs:
            return {"error": "Training dialogs list is empty. Cannot train on empty data."}
        lm.selftrain(request.training_dialogs, epochs=request.epochs, batch_size=request.batch_size)
        lm.use_trained_model()
        return {"message": "Self-training completed and model switched to trained model."}
    except Exception as e:
        import traceback
        print("Self-train error:", e)
        print(traceback.format_exc())
        return {"error": str(e), "traceback": traceback.format_exc()}

@app.post("/interactive-train")
def interactive_train_endpoint(request: InteractiveTrainRequest):
    response, rating = lm.interactivetrain(request.prompt, lambda p, r: request.rating)
    # Here you would save the dialog and rating to your database or resource
    return {"response": response, "rating": rating}

@app.post("/use-trained-model")
def use_trained_model_endpoint():
    lm.use_trained_model()
    return {"message": "Switched to trained model."}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
