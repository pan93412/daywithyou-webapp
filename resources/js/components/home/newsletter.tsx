import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import React from 'react';
import { useForm } from '@inertiajs/react';

export interface NewsletterProps {
    title?: string;
    description?: string;
}

export function Newsletter({
    title = '訂閱電子報',
    description = '訂閱我們的電子報，獲取最新產品資訊和獨家優惠'
}: NewsletterProps) {
    const { data, setData, post, processing, errors, reset, wasSuccessful } = useForm({
        email: '',
    })

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        post(route('inertia-home.subscribe'), {
            preserveScroll: true,
        });
        reset();
    };

    return (
        <section className="bg-emerald-50 py-12">
            <div className="mx-auto max-w-4xl px-4">
                <div className="rounded-xl bg-white p-8 shadow-md">
                    <div className="text-center">
                        <h2 className="mb-2 text-2xl font-bold text-emerald-800">{title}</h2>
                        <p className="mb-6 text-zinc-600">{description}</p>
                    </div>

                    <form onSubmit={handleSubmit} className="mx-auto max-w-md">
                        <div className="flex flex-col gap-3 sm:flex-row">
                            <Input
                                type="email"
                                placeholder="請輸入您的電子郵件"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="flex-1"
                                aria-label="電子郵件"
                            />

                            <Button type="submit" disabled={processing || wasSuccessful} className="whitespace-nowrap">
                                立即訂閱
                            </Button>
                        </div>

                        {wasSuccessful && (
                            <p className="mt-2 text-sm text-emerald-600">感謝您的訂閱！我們將會寄送最新資訊給您。</p>
                        )}

                        {errors.email && (
                            <p className="mt-2 text-sm text-red-600">{errors.email}</p>
                        )}

                        <p className="mt-4 text-center text-xs text-zinc-500">
                            訂閱即表示您同意接收我們的電子報。您可以隨時取消訂閱。
                        </p>
                    </form>
                </div>
            </div>
        </section>
    );
}
