from fastapi import FastAPI, BackgroundTasks
from pydantic import BaseModel
from typing import List, Optional
from AI_selector import select_emphasis
from language_model import SimpleLanguageModel
from fastapi.middleware.cors import CORSMiddleware
import logging
import os
import shutil

app = FastAPI()

# Allow CORS for Laravel frontend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

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
    model_type: Optional[str] = "pretrained"
    user_name: Optional[str] = "User"
    ai_name: Optional[str] = "death_instance_v0.1"

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

def background_self_train(training_dialogs: List[str], epochs: int, batch_size: int):
    try:
        base_dir = os.path.dirname(os.path.abspath(__file__))
        model_dir = os.path.abspath(os.path.join(base_dir, "trained_model"))
        model_files = ["pytorch_model.bin", "model.safetensors", "tf_model.h5", "model.ckpt.index", "flax_model.msgpack"]
        if os.path.exists(model_dir):
            if not any(os.path.exists(os.path.join(model_dir, f)) for f in model_files):
                logger.warning(f"Model directory {model_dir} exists but no model files found. Removing directory to retrain.")
                shutil.rmtree(model_dir)
        lm.selftrain(training_dialogs, epochs=epochs, batch_size=batch_size)
        lm.use_trained_model()
        logger.info("Background self-training completed successfully.")
    except Exception as e:
        logger.error(f"Background self-train error: {e}")

@app.post("/self-train")
def self_train_endpoint(request: SelfTrainRequest, background_tasks: BackgroundTasks):
    if not request.training_dialogs:
        return {"error": "Training dialogs list is empty. Cannot train on empty data."}
    background_tasks.add_task(background_self_train, request.training_dialogs, request.epochs, request.batch_size)
    return {"message": "Self-training started in background."}

@app.post("/interactive-train")
def interactive_train_endpoint(request: InteractiveTrainRequest):
    response, rating = lm.interactivetrain(request.prompt, lambda p, r: request.rating)
    return {"response": response, "rating": rating}

@app.post("/use-trained-model")
def use_trained_model_endpoint():
    lm.use_trained_model()
    return {"message": "Switched to trained model."}

@app.post("/use-pretrained-model")
def use_pretrained_model_endpoint():
    lm.use_pretrained_model()
    return {"message": "Switched to pretrained model."}

@app.post("/generate-text")
def generate_text_endpoint(request: GenerateTextRequest):
    if request.model_type == "custom":
        lm.use_trained_model()
    else:
        lm.use_pretrained_model()
    identity_text = lm._read_identity()
    full_prompt = (
        f"AI name: {request.ai_name}\n"
        f"{identity_text}\n"
        f"user - {request.user_name}'s message : {request.prompt}\n"
        f"ai message : "
    )
    logger.info(f"Generated full prompt: {full_prompt}")
    text = lm.generate_text(full_prompt, max_length=request.max_length)
    logger.info(f"Generated text: {text}")
    return {"generated_text": text}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
