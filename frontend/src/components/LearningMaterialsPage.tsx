import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

// Define the AppUser type
interface AppUser {
    id: number;
    username: string;
    hasTakenQuiz: boolean;
    visual: number;
    auditory: number;
    kinesthetic: number;
}

// Define the LearningMaterial type
interface LearningMaterial {
    id: number;
    title: string;
    visual_perc: number;
    auditory_perc: number;
    kinesthetic_perc: number;
}

export default function LearningMaterialsPage({ user }: { user: AppUser | null }) {
    const [materials, setMaterials] = useState<LearningMaterial[]>([]);
    const [filteredMaterials, setFilteredMaterials] = useState<LearningMaterial[]>([]);

    useEffect(() => {
        const fetchMaterials = async () => {
            try {
                const response = await axios.get<LearningMaterial[]>('/api/materials');
                setMaterials(response.data);
            } catch (error) {
                console.error('Error fetching learning materials:', error);
            }
        };
        fetchMaterials();
    }, []);

    useEffect(() => {
        if (user && materials.length > 0) {
            const total = user.visual + user.auditory + user.kinesthetic;
            const userVisualPercent = total > 0 ? (user.visual / total) * 100 : 0;
            const userAuditoryPercent = total > 0 ? (user.auditory / total) * 100 : 0;
            const userKinestheticPercent = total > 0 ? (user.kinesthetic / total) * 100 : 0;

            const filtered = materials.filter(material => {
                const meetsVisual = userVisualPercent >= material.visual_perc;
                const meetsAuditory = userAuditoryPercent >= material.auditory_perc;
                const meetsKinesthetic = userKinestheticPercent >= material.kinesthetic_perc;
                return meetsVisual && meetsAuditory && meetsKinesthetic;
            });
            setFilteredMaterials(filtered);
        }
    }, [user, materials]);

    if (!user) {
        return (
            <div className="container mt-5 text-center">
                <h2>Please log in to see learning materials.</h2>
            </div>
        );
    }

    if (!user.hasTakenQuiz) {
        return (
            <div className="container mt-5 text-center">
                <h2>Please take the quiz to see your recommended learning materials.</h2>
            </div>
        );
    }

    return (
        <div className="container mt-5">
            <h2 className="text-center mb-4">Recommended Learning Materials</h2>
            <div className="row">
                {filteredMaterials.length > 0 ? (
                    filteredMaterials.map(material => (
                        <div key={material.id} className="col-md-4 mb-4">
                            <Link to={`/materials/${material.id}`} className="text-decoration-none">
                                <div className="card h-100">
                                    <div className="card-body">
                                        <h5 className="card-title">{material.title}</h5>
                                        <p className="card-text">
                                            Visual: {material.visual_perc}%<br/>
                                            Auditory: {material.auditory_perc}%<br/>
                                            Kinesthetic: {material.kinesthetic_perc}%
                                        </p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    ))
                ) : (
                    <p className="text-center">No materials found that match your learning style.</p>
                )}
            </div>
        </div>
    );
}
