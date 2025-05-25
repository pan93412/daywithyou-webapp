import { Button } from '@/components/ui/button';
import { formatDate } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import React from 'react';

interface NewsCardProps {
    title: string;
    summary: string;
    slug: string;
    created_at: Date;
}

const NewsCard: React.FC<NewsCardProps> = ({ title, summary, slug, created_at }) => (
    <div className="flex h-full flex-col rounded-lg bg-white p-6 shadow-md">
        <div className="flex w-full flex-1 flex-col justify-between">
            <div>
                <div className="mb-2 text-sm text-zinc-400">{formatDate(new Date(created_at))}</div>
                <h3 className="mb-3 text-lg font-semibold">{title}</h3>
                <p className="mb-4 line-clamp-3 text-sm text-zinc-500">{summary}</p>
            </div>
            <div className="mt-auto">
                <Link href={route('news.show', { slug })}>
                    <Button variant="outline" className="w-full">
                        閱讀更多
                    </Button>
                </Link>
            </div>
        </div>
    </div>
);

export default NewsCard;
