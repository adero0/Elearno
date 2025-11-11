import React from 'react';
import { useLocation } from 'react-router-dom';

// Define the AppUser type
interface AppUser {
    id: number;
    username: string;
    hasTakenQuiz: boolean;
    visual: number;
    auditory: number;
    kinesthetic: number;
    // Add other fields as necessary
}

export default function ResultsPage() {
    const location = useLocation();
    const user = location.state?.user as AppUser;

    if (!user) {
        return (
            <div className="container mt-5 text-center">
                <h2>No results to display.</h2>
            </div>
        );
    }

    const total = user.visual + user.auditory + user.kinesthetic;
    const visualPercent = total > 0 ? Math.round((user.visual / total) * 100) : 0;
    const auditoryPercent = total > 0 ? Math.round((user.auditory / total) * 100) : 0;
    const kinestheticPercent = total > 0 ? Math.round((user.kinesthetic / total) * 100) : 0;

    return (
        <div className="container mt-5">
            <div className="card">
                <div className="card-body">
                    <h2 className="card-title text-center">Your Learning Style Profile</h2>
                    <div className="mt-4">
                        <p>Visual: {visualPercent}%</p>
                        <div className="progress">
                            <div
                                className="progress-bar"
                                role="progressbar"
                                style={{ width: `${visualPercent}%` }}
                                aria-valuenow={visualPercent}
                                aria-valuemin={0}
                                aria-valuemax={100}
                            ></div>
                        </div>
                    </div>
                    <div className="mt-4">
                        <p>Auditory: {auditoryPercent}%</p>
                        <div className="progress">
                            <div
                                className="progress-bar"
                                role="progressbar"
                                style={{ width: `${auditoryPercent}%` }}
                                aria-valuenow={auditoryPercent}
                                aria-valuemin={0}
                                aria-valuemax={100}
                            ></div>
                        </div>
                    </div>
                    <div className="mt-4">
                        <p>Kinesthetic: {kinestheticPercent}%</p>
                        <div className="progress">
                            <div
                                className="progress-bar"
                                role="progressbar"
                                style={{ width: `${kinestheticPercent}%` }}
                                aria-valuenow={kinestheticPercent}
                                aria-valuemin={0}
                                aria-valuemax={100}
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
