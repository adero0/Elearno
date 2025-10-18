import React, { useState } from "react";
import { motion } from "framer-motion";
import 'bootstrap/dist/css/bootstrap.min.css';

function mapResultToPolish(result: LearningStyle | null) {
    switch (result) {
        case "visual": return "wzrokowiec";
        case "auditory": return "słuchowiec";
        case "kinesthetic": return "kinaestetyk";
        default: return "";
    }
}

type LearningStyle = "visual" | "auditory" | "kinesthetic";

interface Question {
    id: number;
    text: string;
    options: { letter: string; text: string; style: LearningStyle }[];
}

const questions: Question[] = [
    {
        id: 1,
        text: "Kiedy uczysz się nowego zagadnienia, najczęściej:",
        options: [
            { letter: "A", text: "Tworzę schematy lub rysunki", style: "visual" },
            { letter: "B", text: "Słucham wytłumaczenia lub dyskusji", style: "auditory" },
            { letter: "C", text: "Próbuję to zrobić samodzielnie", style: "kinesthetic" },
        ],
    },
    {
        id: 2,
        text: "Gdy zapamiętuję instrukcje, najskuteczniejsze jest dla mnie:",
        options: [
            { letter: "A", text: "Powtarzanie na głos", style: "auditory" },
            { letter: "B", text: "Wyobrażenie sobie drogi lub schematu", style: "visual" },
            { letter: "C", text: "Wykonanie zadania praktycznie", style: "kinesthetic" },
        ],
    },
    {
        id: 3,
        text: "Podczas rozwiązywania problemu:",
        options: [
            { letter: "A", text: "Rozmawiam o problemie", style: "auditory" },
            { letter: "B", text: "Rysuję lub wizualizuję kroki", style: "visual" },
            { letter: "C", text: "Działam praktycznie lub manipuluję obiektami", style: "kinesthetic" },
        ],
    },
    {
        id: 4,
        text: "Kiedy uczę się do egzaminu, wolę:",
        options: [
            { letter: "A", text: "Kolorowe notatki i diagramy", style: "visual" },
            { letter: "B", text: "Nagrać siebie i odsłuchiwać materiał", style: "auditory" },
            { letter: "C", text: "Ćwiczenia praktyczne lub symulacje", style: "kinesthetic" },
        ],
    },
    {
        id: 5,
        text: "Gdy słucham wykładu, najlepiej przyswajam wiedzę gdy:",
        options: [
            { letter: "A", text: "Robię notatki w formie schematów", style: "visual" },
            { letter: "B", text: "Powtarzam sobie na głos", style: "auditory" },
            { letter: "C", text: "Łączę teorię z praktycznym doświadczeniem", style: "kinesthetic" },
        ],
    },
    {
        id: 6,
        text: "Kiedy mam nauczyć się czegoś nowego, zazwyczaj:",
        options: [
            { letter: "A", text: "Wyobrażam sobie proces w głowie", style: "visual" },
            { letter: "B", text: "Dyskutuję z innymi o temacie", style: "auditory" },
            { letter: "C", text: "Próbuję samodzielnie wykonać zadanie", style: "kinesthetic" },
        ],
    },
];

export default function LearningStyleForm(): React.JSX.Element {
    const [responses, setResponses] = useState<Record<number, LearningStyle>>({});
    const [result, setResult] = useState<LearningStyle | null>(null);

    const handleChange = (qId: number, style: LearningStyle) => {
        setResponses({ ...responses, [qId]: style });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const counts: Record<LearningStyle, number> = { visual: 0, auditory: 0, kinesthetic: 0 };
        Object.values(responses).forEach((style) => counts[style]++);
        const dominant = (Object.keys(counts) as LearningStyle[]).reduce((a, b) =>
            counts[a] > counts[b] ? a : b
        );
        setResult(dominant);
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
                        {questions.map((q) => (
                            <div key={q.id} className="mb-4">
                                <p className="fw-bold">{q.text}</p>
                                <div className="d-flex flex-column">
                                    {q.options.map((opt) => (
                                        <div className="form-check" key={opt.letter}>
                                            <input
                                                className="form-check-input"
                                                type="radio"
                                                name={`q-${q.id}`}
                                                value={opt.letter}
                                                onChange={() => handleChange(q.id, opt.style)}
                                                checked={responses[q.id] === opt.style}
                                                required
                                            />
                                            <label className="form-check-label">
                                                {opt.letter}. {opt.text}
                                            </label>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}

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
                                Słuchowiec - wolisz słuch i dyskusje.<br />
                                Kinestetyk - najlepiej przyswajasz wiedzę przez działanie i praktykę.<br />
                            </p>
                        </motion.div>
                    )}
                </div>
            </div>
        </motion.div>
    );
}
