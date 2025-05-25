import AppMainLayout from '@/layouts/app/app-main-layout';
import { formatDate } from '@/lib/utils';
import { Data, News } from '@/types/resource';
import { ArrowLeft } from 'lucide-react';

interface Props {
    newsData: Data<News>;
}

export default function NewsDetails({ newsData }: Props) {
    const { title, content, created_at, updated_at } = newsData.data;

    return (
        <AppMainLayout title={title}>
            <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                {/* Back to News List Button */}
                <button
                    className="mb-6 flex items-center gap-1 text-base font-medium text-emerald-700 hover:underline"
                    onClick={() => window.history.back()}
                    type="button"
                >
                    <ArrowLeft /> 回到消息列表
                </button>

                <div className="rounded-xl bg-white p-8 shadow-lg">
                    <div className="mb-6">
                        <h1 className="mb-3 text-2xl font-bold">{title}</h1>
                        <div className="mb-6 flex items-center text-sm text-zinc-500">
                            <span>發布於 {formatDate(new Date(created_at))}</span>
                            {created_at !== updated_at && <span className="ml-4">更新於 {formatDate(new Date(updated_at))}</span>}
                        </div>
                    </div>

                    <div className="prose prose-emerald max-w-none">
                        <div className="whitespace-pre-line text-zinc-700">{content}</div>
                    </div>
                </div>

                {/* Social Sharing Section */}
                <div className="mt-8 flex flex-col items-center">
                    <h3 className="mb-3 text-lg font-medium">分享這則消息</h3>
                    <div className="flex gap-4">
                        <button className="rounded-full bg-blue-500 p-2 text-white hover:bg-blue-600">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </button>
                        <button className="rounded-full bg-sky-500 p-2 text-white hover:bg-sky-600">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                            </svg>
                        </button>
                        <button className="rounded-full bg-green-500 p-2 text-white hover:bg-green-600">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </main>
        </AppMainLayout>
    );
}
