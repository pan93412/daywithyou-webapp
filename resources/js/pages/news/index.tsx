import NewsCard from '@/components/news-card';
import { Button } from '@/components/ui/button';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { NewsIndex, PaginatedData } from '@/types/resource';
import { Link } from '@inertiajs/react';

interface Props {
    newsReply: PaginatedData<NewsIndex[]>;
}

export default function NewsList({ newsReply }: Props) {
    return (
        <AppMainLayout title="最新消息與活動">
            <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
                <h2 className="mb-8 text-center text-2xl font-bold">最新消息與活動</h2>

                {/* News Grid */}
                <div className="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                    {newsReply.data.map((news) => (
                        <NewsCard key={news.slug} title={news.title} summary={news.summary} slug={news.slug} created_at={news.created_at} />
                    ))}
                </div>

                {/* Pagination Controls */}
                {newsReply.meta.last_page > 1 && (
                    <div className="mt-8 flex items-center justify-center gap-2">
                        {newsReply.meta.links.map((link, index) =>
                            link.url ? (
                                <Link key={index} href={link.url}>
                                    <Button variant={link.active ? 'default' : 'outline'} size="sm" className="min-w-[2.5rem]">
                                        {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                                    </Button>
                                </Link>
                            ) : (
                                <span key={index} className="px-2 text-gray-400">
                                    {link.label.replace(/&laquo;|&raquo;/g, '')}
                                </span>
                            ),
                        )}
                    </div>
                )}

                <div className="mt-4 text-center text-sm text-gray-500">
                    顯示 {newsReply.meta.from} 至 {newsReply.meta.to} 筆，共 {newsReply.meta.total} 筆消息
                </div>
            </main>
        </AppMainLayout>
    );
}
