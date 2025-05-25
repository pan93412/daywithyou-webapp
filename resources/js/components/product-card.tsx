import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import React from 'react';

interface ProductCardProps {
    image: string;
    alt: string;
    name: string;
    description: string;
    price: string;
    id: number;
}

const ProductCard: React.FC<ProductCardProps> = ({ image, alt, name, description, price, id }) => (
    <div className="flex flex-col items-center rounded-lg bg-white p-6 shadow-md">
        <img src={image} alt={alt} className="mb-4 h-32 object-contain" />
        <div className="flex w-full flex-1 flex-col justify-between">
            <div>
                <h3 className="mb-1 text-lg font-semibold">{name}</h3>
                <p className="mb-2 text-sm text-zinc-500">{description}</p>
            </div>
            <div className="mb-3 text-lg font-bold text-primary">NT${price}</div>
            <div className="flex gap-2">
                <Link href={`/products/${id}`}>
                    <Button variant="outline" className="flex-1">
                        查看商品
                    </Button>
                </Link>
                <Button variant="default" className="flex-1">
                    加入購物車
                </Button>
            </div>
        </div>
    </div>
);

export default ProductCard;
