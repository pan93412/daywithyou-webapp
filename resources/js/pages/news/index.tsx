import { NewsIndex, PaginatedData } from '@/types/resource';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import NewsCard from '@/components/news-card';
import { AppContent } from '@/components/app-content';
import { AppHeader } from '@/components/app-header';

interface Props {
    paginatedNewsData: PaginatedData<NewsIndex[]>;
}

export default function NewsList({ paginatedNewsData }: Props) {
    return (
        <AppContent>
            <AppHeader title="最新消息" />

            <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
                <h2 className="mb-8 text-center text-2xl font-bold">最新消息與活動</h2>

                {/* News Grid */}
                <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 mb-8">
                    {paginatedNewsData.data.map((news) => (
                        <NewsCard
                            key={news.slug}
                            title={news.title}
                            summary={news.summary}
                            slug={news.slug}
                            created_at={news.created_at}
                        />
                    ))}
                </div>

                {/* Pagination Controls */}
                {paginatedNewsData.meta.last_page > 1 && (
                    <div className="flex justify-center items-center gap-2 mt-8">
                        {paginatedNewsData.meta.links.map((link, index) => (
                            link.url ? (
                                <Link
                                    key={index}
                                    href={link.url}
                                >
                                    <Button
                                        variant={link.active ? "default" : "outline"}
                                        size="sm"
                                        className="min-w-[2.5rem]"
                                    >
                                        {
                                            link.label
                                                .replace("&laquo;", "←")
                                                .replace("&raquo;", "→")
                                        }
                                    </Button>
                                </Link>
                            ) : (
                                <span key={index} className="px-2 text-gray-400">
                                    {link.label.replace(/&laquo;|&raquo;/g, '')}
                                </span>
                            )
                        ))}
                    </div>
                )}

                <div className="text-center text-sm text-gray-500 mt-4">
                    顯示 {paginatedNewsData.meta.from} 至 {paginatedNewsData.meta.to} 筆，共 {paginatedNewsData.meta.total} 筆消息
                </div>
            </main>
        </AppContent>
    );
}
