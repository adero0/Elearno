import { useState } from 'react';
import LearningStyleForm from './LearningStyleForm';

// Define the AppUser type
interface AppUser {
    id: number;
    username: string;
    hasTakenQuiz: boolean;
    // Add other fields as necessary
}

export default function QuizPage({ user, onQuizComplete }: { user: AppUser, onQuizComplete: (user: AppUser) => void }) {
    const [showQuiz, setShowQuiz] = useState(!user.hasTakenQuiz);

    return (
        <div>
            {showQuiz ? (
                <LearningStyleForm userId={user.id} onQuizComplete={onQuizComplete} />
            ) : (
                <div className="container mt-5 text-center">
                    <h2>Welcome, {user.username}!</h2>
                    <p>You have already taken the learning style quiz.</p>
                    <button className="btn btn-primary" onClick={() => setShowQuiz(true)}>
                        Retake Quiz
                    </button>
                </div>
            )}
        </div>
    );
}
