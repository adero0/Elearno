import React, { useState } from "react";
import { motion } from "framer-motion";
import 'bootstrap/dist/css/bootstrap.min.css';

function mapResultToPolish(result: LearningStyle | null) {
    switch (result) {
        case "visual": return "wzrokowiec";
        case "auditory": return "słuchowiec";
        case "kinesthetic": return "kinestetyk";
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
            { letter: "A", text: "Próbuję to zrobić samodzielnie", style: "kinesthetic" },
            { letter: "B", text: "Tworzę schematy lub rysunki", style: "visual" },
            { letter: "C", text: "Słucham wytłumaczenia lub dyskusji", style: "auditory" },
        ],
    },
    {
        id: 2,
        text: "Gdy zapamiętuję instrukcje, najskuteczniejsze jest dla mnie:",
        options: [
            { letter: "A", text: "Wyobrażenie sobie drogi lub schematu", style: "visual" },
            { letter: "B", text: "Powtarzanie na głos", style: "auditory" },
            { letter: "C", text: "Wykonanie zadania praktycznie", style: "kinesthetic" },
        ],
    },
    {
        id: 3,
        text: "Podczas rozwiązywania problemu:",
        options: [
            { letter: "A", text: "Rozmawiam o problemie", style: "auditory" },
            { letter: "B", text: "Działam praktycznie lub manipuluję obiektami", style: "kinesthetic" },
            { letter: "C", text: "Rysuję lub wizualizuję kroki", style: "visual" },
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
            { letter: "A", text: "Powtarzam sobie na głos", style: "auditory" },
            { letter: "B", text: "Robię notatki w formie schematów", style: "visual" },
            { letter: "C", text: "Łączę teorię z praktycznym doświadczeniem", style: "kinesthetic" },
        ],
    },
    {
        id: 6,
        text: "Kiedy mam nauczyć się czegoś nowego, zazwyczaj:",
        options: [
            { letter: "A", text: "Próbuję samodzielnie wykonać zadanie", style: "kinesthetic" },
            { letter: "B", text: "Wyobrażam sobie proces w głowie", style: "visual" },
            { letter: "C", text: "Dyskutuję z innymi o temacie", style: "auditory" },
        ],
    },
    {
        id: 7,
        text: "Chcąc zacząć oszczędzać, zrobiłbym to w taki sposób:",
        options: [
            { letter: "A", text: "Sprawdzał po kolei, który sposób najlepiej wychodzi", style: "kinesthetic" },
            { letter: "B", text: "Obejrzał filmik na YouTube o oszczędzaniu", style: "auditory" },
            { letter: "C", text: "Na podstawie moich danych finansowych ocenił opcje", style: "visual" },
        ],
    },
    {
        id: 8,
        text: "Po kupieniu stolika z IKEA, masz kłopot z jego złożeniem. Co robisz:",
        options: [
            { letter: "A", text: "Przeglądam rysunki z instrukcji", style: "visual" },
            { letter: "B", text: "Poproszę kogoś kto lepiej się zna o pomoc", style: "kinesthetic" },
            { letter: "C", text: "Oglądam tutorial składania tego stolika", style: "auditory" },
        ],
    },
    {
        id: 9,
        text: "W mojej przyszłej pracy, ważne jest to aby zawierała:",
        options: [
            { letter: "A", text: "Rozmowy z innymi, np. z klientem", style: "auditory" },
            { letter: "B", text: "Pracę z projektami, mapami lub wykresami. ", style: "visual" },
            { letter: "C", text: "Możliwość wykorzystania posiadanej wiedzy", style: "kinesthetic" },
        ],
    },
    {
        id: 10,
        text: "Chcesz nauczyć się nowej gry planszowej lub karcianej. Co robisz:",
        options: [
            { letter: "A", text: "Grał i uczył się w trakcie rozgrywki", style: "kinesthetic" },
            { letter: "B", text: "Poprosił kogoś o wytłumaczenie zasad", style: "auditory" },
            { letter: "C", text: "Przeczytał instrukcję", style: "visual" },
        ],
    },
];

// {
//         id: ,
//         text: "",
//         options: [
//             { letter: "A", text: "", style: "" },
//             { letter: "B", text: "", style: "" },
//             { letter: "C", text: "", style: "" },
//         ],
//     },

export default function LearningStyleForm(): React.JSX.Element {
    // store each question’s selected learning styles as an array
    const [responses, setResponses] = useState<Record<number, LearningStyle[]>>({});
    const [result, setResult] = useState<LearningStyle | null>(null);

    const handleChange = (qId: number, style: LearningStyle) => {
        const current = responses[qId] || [];
        if (current.includes(style)) {
            // uncheck: remove from array
            setResponses({ ...responses, [qId]: current.filter((s) => s !== style) });
        } else {
            // check: add to array
            setResponses({ ...responses, [qId]: [...current, style] });
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        const counts: Record<LearningStyle, number> = { visual: 0, auditory: 0, kinesthetic: 0 };

        Object.values(responses).forEach((styles) => {
            styles.forEach((style) => counts[style]++);
        });

        const dominant = (Object.keys(counts) as LearningStyle[]).reduce((a, b) =>
            counts[a] > counts[b] ? a : b
        );

        setResult(dominant);
        console.log("Counts:", counts);
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