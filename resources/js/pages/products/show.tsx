import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Product } from '@/types/products';
import { Data } from '@/types/resource';
import { AppHeader } from '@/components/app-header';
import { ArrowLeft } from 'lucide-react';

interface Props {
    productData: Data<Product>,
}

export default function ProductDetails({ productData }: Props) {
    const { image, name, price, description } = productData.data;

    return (
        <div className="flex min-h-screen flex-col bg-zinc-50">
            <AppHeader title={name} />
            <main className="flex-1 w-full max-w-4xl mx-auto py-8 px-4">
                {/* Back to Product List Button */}
                <button
                    className="mb-6 text-emerald-700 hover:underline text-base font-medium flex items-center gap-1"
                    onClick={() => window.history.back()}
                    type="button"
                >
                    <ArrowLeft /> 回到商品列表
                </button>
                <div className="flex flex-col md:flex-row gap-8 bg-white rounded-xl shadow-lg p-8">
                    <div className="flex-shrink-0 flex justify-center items-center md:w-1/2">
                        <img
                            src={image}
                            alt={name}
                            className="rounded-lg object-contain max-h-72 w-full border"
                        />
                    </div>
                    <div className="flex flex-col gap-4 md:w-1/2">
                        <h1 className="text-2xl font-bold mb-2">{name}</h1>
                        <div className="text-emerald-600 text-xl font-semibold mb-2">${price}</div>
                        <p className="text-zinc-700 mb-4 whitespace-pre-line">{description}</p>
                        <div className="flex items-center gap-2 mb-4">
                            <span>數量：</span>
                            <Input type="number" min={1} defaultValue={1} className="w-20" />
                        </div>
                        <div className="flex gap-4">
                            <Button className="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white">加入購物車</Button>
                            <Button className="flex-1" variant="outline">立刻購買</Button>
                        </div>
                    </div>
                </div>
                {/* Mock Comment & Rate Area */}
                <section className="mt-10 px-2">
                    <h2 className="text-lg font-semibold mb-3">商品評價</h2>
                    <div className="flex items-center gap-2 mb-2">
                        <span className="text-yellow-500 text-xl">★★★★★</span>
                        <span className="text-zinc-600 text-sm">4.8 / 5.0 (123 則評價)</span>
                    </div>
                    <div className="mb-6">
                        <p className="text-zinc-800 font-medium">使用者A</p>
                        <div className="text-yellow-500 text-base">★★★★☆</div>
                        <p className="text-zinc-600 text-sm">商品品質很好，出貨速度快，下次還會再購買！</p>
                    </div>
                    <div className="mb-6">
                        <p className="text-zinc-800 font-medium">使用者B</p>
                        <div className="text-yellow-500 text-base">★★★★★</div>
                        <p className="text-zinc-600 text-sm">非常滿意，客服也很親切，推薦！</p>
                    </div>
                    <div className="mb-6">
                        <p className="text-zinc-800 font-medium">使用者C</p>
                        <div className="text-yellow-500 text-base">★★★★☆</div>
                        <p className="text-zinc-600 text-sm">整體不錯，但包裝還可以再加強。</p>
                    </div>
                </section>
            </main>
        </div>
    );
}
