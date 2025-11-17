import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from "axios"
import { useNavigate } from 'react-router-dom';

// Define the AppUser type
interface AppUser {
    id: number;
    username: string;
    hasTakenQuiz: boolean;
    visual: number;
    auditory: number;
    kinesthetic: number;
}

// Update the mapResultToPolish function
function mapResultToPolish(result: LearningStyle | null) {
    switch (result) {
        case "visual": return "wzrokowiec";
        case "auditory": return "słuchowiec";
        case "kinesthetic": return "kinestetyk";
        default: return "";
    }
}

// Define the LearningStyle type and Quiz interfaces
type LearningStyle = "visual" | "auditory" | "kinesthetic";

interface Option {
    letter: string;
    text: string;
    style: LearningStyle;
}

interface Question {
    id: number;
    text: string;
    options: Option[];
}

interface Quizes {
    [key: string]: Question[][];
}

export default function LearningStyleForm({ userId, onQuizComplete }: { userId: number, onQuizComplete: (user: AppUser) => void }): React.JSX.Element {
    // State to hold the randomly selected quiz
    const [questions, setQuestions] = useState<Question[]>([]);

    // State to store each question’s selected learning styles as an array
    const [responses, setResponses] = useState<Record<number, LearningStyle[]>>({});
    const [result, setResult] = useState<LearningStyle | null>(null);
    const [formError, setFormError] = useState<string | null>(null); // Error message state
    const navigate = useNavigate();

    // Fetch the quiz data from the JSON file when the component mounts
    useEffect(() => {
        const fetchQuizData = async () => {
            try {
                const response = await fetch("/quizzes.json");
                const data: Quizes = await response.json();

                let allQuizzes: Question[][] = [];
                for (const key in data) {
                    if (Object.prototype.hasOwnProperty.call(data, key)) {
                        allQuizzes = allQuizzes.concat(data[key]);
                    }
                }

                // Randomly select one quiz from the combined array of all quizzes
                const randomIndex = Math.floor(Math.random() * allQuizzes.length);
                setQuestions(allQuizzes[randomIndex]);
            } catch (error) {
                console.error("Error loading quiz data:", error);
            }
        };

        fetchQuizData();
    }, []);

    // Handle option change
    const handleChange = (qId: number, style: LearningStyle) => {
        const current = responses[qId] || [];
        if (current.includes(style)) {
            setResponses({ ...responses, [qId]: current.filter((s) => s !== style) });
        } else {
            setResponses({ ...responses, [qId]: [...current, style] });
        }
    };

    // Handle form submit to calculate dominant learning style
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        // Check if every question has at least one response
        const allQuestionsAnswered = questions.every((q) => responses[q.id]?.length > 0);

        if (!allQuestionsAnswered) {
            setFormError("Musisz odpowiedzieć na wszystkie pytania.");
            return;
        } else {
            setFormError(null); // Clear error message if all questions are answered
        }

        const counts: Record<LearningStyle, number> = {visual: 0, auditory: 0, kinesthetic: 0};

        // Count the responses for each learning style
        Object.values(responses).forEach((styles) => {
            styles.forEach((style) => counts[style]++);
        });

        // Find the dominant learning style based on counts
        const dominant = (Object.keys(counts) as LearningStyle[]).reduce((a, b) =>
            counts[a] > counts[b] ? a : b
        );

        setResult(dominant);
        console.log("Counts:", counts);

        const quizData = {
            visualCount: counts.visual,
            auditoryCount: counts.auditory,
            kinestheticCount: counts.kinesthetic,
            dominantStyle: dominant,
        };

        try {
            // Send the data via POST request using Axios
            const response = await axios.post<AppUser>(`/api/quiz/update/${userId}`, quizData);

            if (response.status === 200) {
                console.log("Quiz data submitted successfully.");
                onQuizComplete(response.data);
                navigate('/results', { state: { user: response.data } });
            } else {
                console.error("Failed to submit quiz data.");
            }
        } catch (error) {
            console.error("Error submitting quiz data:", error);
        }
    };

    return (
        <motion.div
            className="container mt-5"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
        >
            <div className="card shadow">
                <div className="card-body">
                    <h2 className="card-title text-center mb-4">Test Stylu Uczenia się</h2>

                    <form onSubmit={handleSubmit}>
                        {questions.length > 0 ? (
                            questions.map((q) => (
                                <div key={q.id} className="mb-4">
                                    <p className="fw-bold">{q.text}</p>
                                    <div className="d-flex flex-column">
                                        {q.options.map((opt) => (
                                            <div className="form-check" key={opt.letter}>
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    name={`q-${q.id}`}
                                                    value={opt.letter}
                                                    onChange={() => handleChange(q.id, opt.style)}
                                                    checked={responses[q.id]?.includes(opt.style) || false}
                                                />
                                                <label className="form-check-label">
                                                    {opt.letter}. {opt.text}
                                                </label>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p>Loading quiz questions...</p>
                        )}

                        {formError && (
                            <div className="alert alert-danger mt-3" role="alert">
                                {formError}
                            </div>
                        )}

                        <div className="text-center">
                            <button type="submit" className="btn btn-primary">
                                Zakończ
                            </button>
                        </div>
                    </form>

                    {result && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            className="text-center mt-4"
                        >
                            <h3 className="fw-semibold">Twój dominujący styl uczenia się:</h3>
                            <p className="display-6 text-capitalize">{mapResultToPolish(result)}</p>
                            <p className="text-muted mt-2">
                                Opis styli uczenia: <br />
                                Wzrokowiec - uczysz się najlepiej poprzez obrazy i schematy.<br />
                                Słuchowiec - wolisz słuchać lub rozmawiać.<br />
                                Kinestetyk - najlepiej przyswajasz wiedzę przez działanie i praktykę.<br />
                            </p>
                        </motion.div>
                    )}
                </div>
            </div>
        </motion.div>
    );
}
