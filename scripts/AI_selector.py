from typing import List, Dict
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np

def select_most_relevant(text: str, candidates: List[str]) -> Dict[str, any]:
    """
    Given a text and a list of candidate texts (skills, experiences, formations),
    return the candidate with the highest similarity score.
    """
    if not candidates:
        return {"selected_index": -1, "selected_text": None, "similarity_score": 0.0}

    documents = [text] + candidates
    vectorizer = TfidfVectorizer().fit_transform(documents)
    vectors = vectorizer.toarray()

    text_vector = vectors[0].reshape(1, -1)
    candidate_vectors = vectors[1:]

    similarities = cosine_similarity(text_vector, candidate_vectors).flatten()
    max_index = int(np.argmax(similarities))
    max_similarity = float(similarities[max_index])

    return {
        "selected_index": max_index,
        "selected_text": candidates[max_index],
        "similarity_score": max_similarity
    }

def select_emphasis(job_title: str, job_description: str,
                    skills: List[str], experiences: List[str], formations: List[str]) -> Dict[str, Dict]:
    """
    Select which skill, experience, and formation is most relevant to the job title and description.
    """
    combined_text = job_title + " " + job_description

    skill_selection = select_most_relevant(combined_text, skills)
    experience_selection = select_most_relevant(combined_text, experiences)
    formation_selection = select_most_relevant(combined_text, formations)

    return {
        "skill": skill_selection,
        "experience": experience_selection,
        "formation": formation_selection
    }

if __name__ == "__main__":
    job_title = "Software Engineer"
    job_description = "Develop and maintain web applications using Python and JavaScript."
    skills = ["Python", "JavaScript", "Project Management", "Communication"]
    experiences = ["3 years at Company A", "2 years at Company B", "Internship at Company C"]
    formations = ["Bachelor's in Computer Science", "Master's in Software Engineering"]

    result = select_emphasis(job_title, job_description, skills, experiences, formations)
    print("Emphasis selection:")
    print(result)
