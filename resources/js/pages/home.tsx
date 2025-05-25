import { AppHeader } from '@/components/app-header';
import ProductCard from '@/components/product-card';
import { ProductIndex } from '@/types/products';
import { Data } from '@/types/resource';

interface Props {
    hotProductsData: Data<ProductIndex[]>;
}

export default function Home({ hotProductsData }: Props) {
    return (
        <div className="flex min-h-screen flex-col bg-zinc-50">
            <AppHeader title="首頁" />

            {/* 熱門商品區塊 */}
            <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
                <h2 className="mb-8 text-center text-2xl font-bold">熱門商品</h2>
                <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                    {hotProductsData.data.map((productIndex) => (
                        <ProductCard
                            key={productIndex.id}
                            image={productIndex.image}
                            alt={productIndex.name}
                            name={productIndex.name}
                            description={productIndex.description}
                            price={productIndex.price}
                            id={productIndex.id}
                        />
                    ))}
                </div>
            </main>
        </div>
    );
}
