import React, { useState, useEffect } from 'react';

interface Option {
    id: string;
    text: string;
}

interface Question {
    id: number;
    text: string;
    options: Option[];
    correctAnswer: string;
}

interface Quiz {
    title: string;
    questions: Question[];
}

interface KnowledgeQuizzes {
    [key: string]: Quiz;
}

export default function KnowledgeQuiz({ quizId }: { quizId: string }) {
    const [quiz, setQuiz] = useState<Quiz | null>(null);
    const [answers, setAnswers] = useState<Record<number, string>>({});
    const [score, setScore] = useState<number | null>(null);

    useEffect(() => {
        const fetchQuiz = async () => {
            try {
                const response = await fetch('/knowledge_quizzes.json');
                const quizzes: KnowledgeQuizzes = await response.json();
                if (quizzes[quizId]) {
                    setQuiz(quizzes[quizId]);
                }
            } catch (error) {
                console.error('Error fetching knowledge quiz:', error);
            }
        };
        fetchQuiz();
    }, [quizId]);

    const handleOptionChange = (questionId: number, optionId: string) => {
        setAnswers({
            ...answers,
            [questionId]: optionId,
        });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!quiz) return;

        let correctCount = 0;
        quiz.questions.forEach(q => {
            if (answers[q.id] === q.correctAnswer) {
                correctCount++;
            }
        });
        setScore(correctCount);
    };

    if (!quiz) {
        return <div>Loading quiz...</div>;
    }

    if (score !== null) {
        return (
            <div className="card mt-4">
                <div className="card-body">
                    <h3 className="card-title text-center">Quiz Results</h3>
                    <p className="text-center fs-4">
                        You scored {score} out of {quiz.questions.length}!
                    </p>
                </div>
            </div>
        );
    }

    return (
        <div className="card mt-4">
            <div className="card-body">
                <h3 className="card-title text-center">{quiz.title}</h3>
                <form onSubmit={handleSubmit}>
                    {quiz.questions.map(q => (
                        <div key={q.id} className="mb-4">
                            <p className="fw-bold">{q.text}</p>
                            {q.options.map(opt => (
                                <div className="form-check" key={opt.id}>
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name={`question-${q.id}`}
                                        id={`q${q.id}-opt${opt.id}`}
                                        value={opt.id}
                                        onChange={() => handleOptionChange(q.id, opt.id)}
                                        checked={answers[q.id] === opt.id}
                                    />
                                    <label className="form-check-label" htmlFor={`q${q.id}-opt${opt.id}`}>
                                        {opt.text}
                                    </label>
                                </div>
                            ))}
                        </div>
                    ))}
                    <div className="text-center">
                        <button type="submit" className="btn btn-primary">
                            Submit Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
