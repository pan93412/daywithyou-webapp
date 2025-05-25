import ProductComments, { ProductCommentsSkeleton } from '@/components/products/comments';
import NewComments from '@/components/products/new-comments';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { Comment, Data, Product } from '@/types/resource';
import { Deferred, router } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { useState } from 'react';
import { toast } from 'sonner';

interface Props {
    productReply: Data<Product>;
    commentsReply?: Data<Comment[]>;
}

export default function ProductDetails({ productReply, commentsReply }: Props) {
    const { figure, name, price, description } = productReply.data;
    const [quantity, setQuantity] = useState(1);

    const handleAddToCart = () => {
        router.post(
            route('carts.increment', { product: productReply.data.slug }),
            {
                quantity,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.info('已加入購物車！');
                },
                onError(error) {
                    for (const value of Object.values(error)) {
                        toast.error(value);
                    }
                },
            },
        );
    };

    return (
        <AppMainLayout title={name}>
            <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                {/* Back to Product List Button */}
                <button
                    className="mb-6 flex items-center gap-1 text-base font-medium text-emerald-700 hover:underline"
                    onClick={() => window.history.back()}
                    type="button"
                >
                    <ArrowLeft /> 回到商品列表
                </button>
                <div className="flex flex-col gap-8 rounded-xl bg-white p-8 shadow-lg md:flex-row">
                    <div className="flex flex-shrink-0 items-center justify-center md:w-1/2">
                        <img src={figure} alt={name} className="max-h-72 w-full rounded-lg border object-contain" />
                    </div>
                    <div className="flex flex-col gap-4 md:w-1/2">
                        <h1 className="mb-2 text-2xl font-bold">{name}</h1>
                        <div className="text-primary mb-2 text-xl font-semibold">NT${price}</div>
                        <p className="mb-4 whitespace-pre-line text-zinc-700">{description}</p>
                        <div className="mb-4 flex items-center gap-2">
                            <span>數量：</span>
                            <Input type="number" min={1} value={quantity} onChange={(e) => setQuantity(Number(e.target.value))} className="w-20" />
                        </div>
                        <div className="flex gap-4">
                            <Button className="bg-primary hover:bg-primary/90 flex-1 text-white" onClick={handleAddToCart}>
                                加入購物車
                            </Button>
                            <Button className="flex-1" variant="outline">
                                立刻購買
                            </Button>
                        </div>
                    </div>
                </div>

                {/* Mock Comment & Rate Area */}
                <section className="mt-10 px-2">
                    <h2 className="mb-3 text-lg font-semibold">商品評價</h2>
                    <div className="mb-4">
                        <NewComments product={productReply.data.slug} />
                    </div>
                    <Deferred fallback={<ProductCommentsSkeleton />} data="commentsReply">
                        <ProductComments comments={commentsReply?.data ?? []} />
                    </Deferred>
                </section>
            </main>
        </AppMainLayout>
    );
}
