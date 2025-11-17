import { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Navbar from './components/Navbar';
import MainPage from './components/MainPage';
import QuizPage from './components/QuizPage';
import ResultsPage from './components/ResultsPage';
import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';

// Define the AppUser type
interface AppUser {
    id: number;
    username: string;
    hasTakenQuiz: boolean;
    visual: number;
    auditory: number;
    kinesthetic: number;
}

function App() {
    const [user, setUser] = useState<AppUser | null>(null);

    const handleLogin = (userData: AppUser) => setUser(userData);
    const handleLogout = () => setUser(null);
    const handleQuizComplete = (userData: AppUser) => setUser(userData);

    return (
        <Router>
            <Navbar />
            <Routes>
                <Route path="/login" element={<LoginPage onLogin={handleLogin} />} />
                <Route path="/register" element={<RegisterPage />} />
                <Route
                    path="/"
                    element={user ? <MainPage /> : <Navigate to="/login" />}
                />
                <Route
                    path="/quiz"
                    element={user ? <QuizPage user={user} onQuizComplete={handleQuizComplete} /> : <Navigate to="/login" />}
                />
                <Route
                    path="/results"
                    element={user ? <ResultsPage /> : <Navigate to="/login" />}
                />
            </Routes>
        </Router>
    );
}

export default App;



