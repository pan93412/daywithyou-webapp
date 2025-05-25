import React from 'react';
import { Link } from '@inertiajs/react';

interface NewsItem {
    id: number;
    title: string;
    url: string;
}

interface NewsBannerProps {
    news: NewsItem[];
}

export const NewsBanner: React.FC<NewsBannerProps> = ({ news }) => {
    const [currentIndex, setCurrentIndex] = React.useState(0);
    const [isAnimating, setIsAnimating] = React.useState(false);
    const [prevIndex, setPrevIndex] = React.useState(0);

    React.useEffect(() => {
        if (news.length <= 1) return;
        
        const interval = setInterval(() => {
            setPrevIndex(currentIndex);
            setIsAnimating(true);
            
            // Wait for animation to start before changing the index
            setTimeout(() => {
                setCurrentIndex((prevIndex) => (prevIndex + 1) % news.length);
            }, 50);
            
            // Reset animation state after transition completes
            setTimeout(() => {
                setIsAnimating(false);
            }, 500);
        }, 5000);

        return () => clearInterval(interval);
    }, [currentIndex, news.length]);

    if (news.length === 0) return null;

    return (
        <section className="bg-emerald-50 py-3">
            <div className="mx-auto flex max-w-6xl items-center justify-between px-4">
                <div className="flex items-center">
                    <span className="mr-3 rounded-md bg-emerald-600 px-2 py-1 text-xs font-bold text-white">
                        最新消息
                    </span>
                    <div className="relative overflow-hidden" style={{ height: '24px', width: '500px' }}>
                        {news.map((item, index) => (
                            <div
                                key={item.id}
                                className="absolute left-0 top-0 w-full transition-all duration-500 ease-in-out"
                                style={{
                                    opacity: index === currentIndex ? 1 : 0,
                                    transform: `translateY(${index === currentIndex ? '0' : 
                                        (isAnimating && index === prevIndex) ? '-100%' : '100%'})`,
                                    pointerEvents: index === currentIndex ? 'auto' : 'none',
                                }}
                            >
                                <Link href={item.url} className="block truncate hover:text-emerald-600">
                                    {item.title}
                                </Link>
                            </div>
                        ))}
                    </div>
                </div>
                <Link href="/news" className="text-sm text-emerald-600 hover:underline">
                    查看全部
                </Link>
            </div>
        </section>
    );
};
