import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { SharedData } from '@/types';
import { ProductIndex } from '@/types/resource';
import { Link, router, usePage } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { FormEvent, useState } from 'react';
import { toast } from 'sonner';

interface CartItem {
    data: ProductIndex;
    quantity: number;
}

interface Props {
    carts: CartItem[];
}

export default function CheckoutPage({ carts }: Props) {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const { auth } = usePage<SharedData>().props;
    const totalPrice = carts.reduce((sum, { data: product, quantity }) => sum + Number(product.price) * quantity, 0);

    const [formData, setFormData] = useState({
        name: auth.user.name,
        email: auth.user.email,
        phone: '',
        address: '',
        city: '',
        zipCode: '',
        paymentMethod: 'cash',
    });

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSelectChange = (name: string, value: string) => {
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        // Simulate form submission
        setTimeout(() => {
            toast.success('訂單已成功提交');
            router.visit(route('orders.confirmation'));
            setIsSubmitting(false);
        }, 1500);
    };

    if (carts.length === 0) {
        return (
            <AppMainLayout title="結帳">
                <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                    <div className="flex flex-col items-center justify-center rounded-xl bg-white p-12 shadow-md">
                        <h2 className="mb-2 text-xl font-semibold">購物車是空的</h2>
                        <p className="mb-6 text-center text-zinc-500">請先將商品加入購物車再進行結帳</p>
                        <Button onClick={() => router.visit(route('products.index'))}>繼續購物</Button>
                    </div>
                </main>
            </AppMainLayout>
        );
    }

    return (
        <AppMainLayout title="結帳">
            <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                <div className="mb-6">
                    <Link href={route('carts.index')} className="mb-6 flex items-center gap-1 text-base font-medium text-emerald-700 hover:underline">
                        <ArrowLeft /> 返回購物車
                    </Link>
                    <h1 className="text-2xl font-bold">結帳頁面</h1>
                    <p className="text-zinc-500">請填寫以下資訊完成訂購</p>
                </div>

                <form onSubmit={handleSubmit} className="flex flex-col gap-6 lg:flex-row">
                    <div className="flex-1">
                        <div className="rounded-xl bg-white p-6 shadow-md">
                            <h2 className="mb-4 text-lg font-semibold">收件資訊</h2>

                            <div className="space-y-4">
                                <div>
                                    <label htmlFor="name" className="mb-1 block text-sm font-medium">
                                        姓名
                                    </label>
                                    <Input
                                        id="name"
                                        name="name"
                                        value={formData.name}
                                        onChange={handleInputChange}
                                        placeholder="請輸入姓名"
                                        required
                                    />
                                </div>

                                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label htmlFor="email" className="mb-1 block text-sm font-medium">
                                            電子郵件
                                        </label>
                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value={formData.email}
                                            onChange={handleInputChange}
                                            placeholder="請輸入電子郵件"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="phone" className="mb-1 block text-sm font-medium">
                                            手機號碼
                                        </label>
                                        <Input
                                            id="phone"
                                            name="phone"
                                            value={formData.phone}
                                            onChange={handleInputChange}
                                            placeholder="請輸入手機號碼"
                                            required
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label htmlFor="address" className="mb-1 block text-sm font-medium">
                                        地址
                                    </label>
                                    <Input
                                        id="address"
                                        name="address"
                                        value={formData.address}
                                        onChange={handleInputChange}
                                        placeholder="請輸入詳細地址"
                                        required
                                    />
                                </div>

                                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label htmlFor="city" className="mb-1 block text-sm font-medium">
                                            城市
                                        </label>
                                        <Input
                                            id="city"
                                            name="city"
                                            value={formData.city}
                                            onChange={handleInputChange}
                                            placeholder="請輸入城市"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="zipCode" className="mb-1 block text-sm font-medium">
                                            郵遞區號
                                        </label>
                                        <Input
                                            id="zipCode"
                                            name="zipCode"
                                            value={formData.zipCode}
                                            onChange={handleInputChange}
                                            placeholder="請輸入郵遞區號"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="mt-6 rounded-xl bg-white p-6 shadow-md">
                            <h2 className="mb-4 text-lg font-semibold">付款方式</h2>

                            <div className="space-y-4">
                                <div>
                                    <label htmlFor="paymentMethod" className="mb-1 block text-sm font-medium">
                                        選擇付款方式
                                    </label>
                                    <Select value={formData.paymentMethod} onValueChange={(value) => handleSelectChange('paymentMethod', value)}>
                                        <SelectTrigger id="paymentMethod" className="w-full">
                                            <SelectValue placeholder="選擇付款方式" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="cash">現金付款</SelectItem>
                                            <SelectItem value="line_pay">Line Pay</SelectItem>
                                            <SelectItem value="bank_transfer">銀行轉帳</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="lg:w-80">
                        <div className="sticky top-24 rounded-xl bg-white p-6 shadow-md">
                            <h2 className="mb-4 text-lg font-semibold">訂單摘要</h2>

                            <div className="mb-4 max-h-60 overflow-y-auto">
                                {carts.map(({ data: product, quantity }) => (
                                    <div key={product.slug} className="mb-3 flex items-center gap-2">
                                        <div className="h-12 w-12 flex-shrink-0">
                                            <img src={product.figure} alt={product.name} className="h-full w-full rounded-md border object-contain" />
                                        </div>
                                        <div className="flex-1 text-sm">
                                            <div className="font-medium">{product.name}</div>
                                            <div className="flex justify-between">
                                                <span className="text-zinc-500">x{quantity}</span>
                                                <span>NT${Number(product.price) * quantity}</span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="space-y-2">
                                <div className="flex justify-between">
                                    <span>小計 ({carts.length} 件商品)</span>
                                    <span>NT${totalPrice}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>運費</span>
                                    <span>免費</span>
                                </div>
                                <div className="border-t border-zinc-100 pt-2">
                                    <div className="flex justify-between font-semibold">
                                        <span>總計</span>
                                        <span className="text-primary">NT${totalPrice}</span>
                                    </div>
                                </div>
                            </div>

                            <Button type="submit" className="mt-6 w-full" disabled={isSubmitting}>
                                {isSubmitting ? '處理中...' : '提交訂單'}
                            </Button>
                        </div>
                    </div>
                </form>
            </main>
        </AppMainLayout>
    );
}
