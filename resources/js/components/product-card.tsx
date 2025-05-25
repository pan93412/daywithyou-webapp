import { Button } from '@/components/ui/button';
import { Link, router } from '@inertiajs/react';
import { useCallback } from 'react';
import { toast } from 'sonner';

export interface ProductCardProps {
    figure: string;
    alt: string;
    name: string;
    summary: string;
    price: string;
    slug: string;
}

export default function ProductCard({ figure, alt, name, summary, price, slug }: ProductCardProps) {
    const handleAddToCart = useCallback(async () => {
        router.post(
            route('carts.increment', { product: slug }),
            {
                quantity: 1,
            },
            {
                preserveScroll: true,
            },
        );

        toast.info('已加入購物車');
    }, [slug]);

    return (
        <div className="flex flex-col items-center rounded-lg bg-white p-6 shadow-md">
            <img src={figure} alt={alt} className="mb-4 h-32 object-contain" />
            <div className="flex w-full flex-1 flex-col justify-between">
                <div>
                    <h3 className="mb-1 text-lg font-semibold">{name}</h3>
                    <p className="mb-2 text-sm text-zinc-500">{summary}</p>
                </div>
                <div className="text-primary mb-3 text-lg font-bold">NT${price}</div>
                <div className="flex gap-2">
                    <Link href={route('products.show', { product: slug })}>
                        <Button variant="outline" className="flex-1">
                            查看商品
                        </Button>
                    </Link>
                    <Button variant="default" className="flex-1" onClick={handleAddToCart}>
                        加入購物車
                    </Button>
                </div>
            </div>
        </div>
    );
}
