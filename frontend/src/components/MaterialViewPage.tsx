import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import KnowledgeQuiz from './KnowledgeQuiz';

// Define types
interface LearningMaterial {
    id: number;
    title: string;
    visual_perc: number;
    auditory_perc: number;
    kinesthetic_perc: number;
    content: string; // This will be a JSON string
}

interface ContentBlock {
    type: 'text' | 'video' | 'audio' | 'quiz';
    path?: string; // For files (text, video, audio)
    quizId?: string; // For quizzes
    // The rendered text content for 'text' type will be stored here after fetching
    fetchedText?: string;
}

export default function MaterialViewPage() {
    const { id } = useParams<{ id: string }>();
    const [material, setMaterial] = useState<LearningMaterial | null>(null);
    const [contentBlocks, setContentBlocks] = useState<ContentBlock[]>([]);
    const [error, setError] = useState<string | null>(null);
    const [loadingContent, setLoadingContent] = useState<boolean>(true); // New loading state for content

    useEffect(() => {
        const fetchMaterialAndContent = async () => {
            setLoadingContent(true);
            try {
                const response = await axios.get<LearningMaterial>(`/api/materials/${id}`);
                setMaterial(response.data);
                // Parse the content string into a JSON object
                if (response.data.content) {
                    const parsedBlocks: ContentBlock[] = JSON.parse(response.data.content);

                    // Fetch content for text blocks
                    const blocksWithFetchedText = await Promise.all(parsedBlocks.map(async (block) => {
                        if (block.type === 'text' && block.path) {
                            try {
                                const textResponse = await fetch(block.path);
                                const text = await textResponse.text();
                                return { ...block, fetchedText: text };
                            } catch (textErr) {
                                console.error(`Error fetching text from ${block.path}:`, textErr);
                                return { ...block, fetchedText: `(Error loading text from ${block.path})` };
                            }
                        }
                        return block;
                    }));
                    setContentBlocks(blocksWithFetchedText);
                }
            } catch (err) {
                setError('Failed to load learning material.');
                console.error(err);
            } finally {
                setLoadingContent(false);
            }
        };

        if (id) {
            fetchMaterialAndContent();
        }
    }, [id]);

    const renderContentBlock = (block: ContentBlock, index: number) => {
        switch (block.type) {
            case 'text':
                return <p key={index}>{block.fetchedText}</p>;
            case 'video':
                return (
                    <div key={index} className="my-3">
                        <video controls width="100%">
                            <source src={block.path} type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>
                );
            case 'audio':
                 return (
                    <div key={index} className="my-3">
                        <audio controls className="w-100">
                            <source src={block.path} type="audio/mpeg" />
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                );
            case 'quiz':
                return <KnowledgeQuiz key={index} quizId={block.quizId || ''} />;
            default:
                return <p key={index}>Unsupported content type.</p>;
        }
    };

    if (error) {
        return <div className="container mt-5 text-center"><h2>{error}</h2></div>;
    }

    if (!material || loadingContent) {
        return <div className="container mt-5 text-center"><h2>Loading...</h2></div>;
    }

    return (
        <div className="container mt-5">
            <h1 className="mb-4">{material.title}</h1>
            <div className="card">
                <div className="card-body">
                    {contentBlocks.length > 0 ? (
                        contentBlocks.map(renderContentBlock)
                    ) : (
                        <p>This material has no content.</p>
                    )}
                </div>
            </div>
        </div>
    );
}
