import React from 'react';
import { Link } from 'react-router-dom';

export default function MainPage() {
    return (
        <div className="container mt-5 text-center">
            <h2>Welcome to Elearno!</h2>
            <p>This is the main page. You can navigate to the quiz from here.</p>
            <Link to="/quiz" className="btn btn-primary">
                Go to Quiz
            </Link>
            <Link to="/materials" className="btn btn-secondary ms-2">
                View Learning Materials
            </Link>
        </div>
    );
}
