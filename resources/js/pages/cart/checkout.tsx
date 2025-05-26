import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { SharedData } from '@/types';
import { ProductIndex } from '@/types/resource';
import { Link, router, useForm, usePage } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { FormEvent } from 'react';
import { toast } from 'sonner';

interface CartItem {
    data: ProductIndex;
    quantity: number;
}

interface Props {
    carts: CartItem[];
}

interface CheckoutForm {
    recipient_name: string;
    recipient_email: string;
    recipient_phone: string;
    recipient_address: string;
    recipient_city: string;
    recipient_zip_code: string;
    note: string;
    payment_method: 'cash' | 'line_pay' | 'bank_transfer' | string;
}

export default function CheckoutPage({ carts }: Props) {
    const { auth } = usePage<SharedData>().props;
    const totalPrice = carts.reduce((sum, { data: product, quantity }) => sum + Number(product.price) * quantity, 0);

    const { data, setData, errors, post, processing } = useForm<Required<CheckoutForm>>({
        recipient_name: auth.user.name,
        recipient_email: auth.user.email,
        recipient_phone: auth.user.phone ?? '',
        recipient_address: auth.user.address ?? '',
        recipient_city: auth.user.city ?? '',
        recipient_zip_code: auth.user.zip ?? '',
        note: '',
        payment_method: 'cash',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();

        post(route('orders.store'), {
            onSuccess: () => {
                toast.success('已經送出訂單！');
            },
            onError(error) {
                for (const value of Object.values(error)) {
                    toast.error(value);
                }
            },
        });
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
                                    <label htmlFor="recipient-name" className="mb-1 block text-sm font-medium">
                                        姓名
                                    </label>
                                    <Input
                                        id="recipient-name"
                                        name="recipient-name"
                                        value={data.recipient_name}
                                        onChange={(e) => setData('recipient_name', e.target.value)}
                                        placeholder="請輸入姓名"
                                        required
                                    />
                                    {errors.recipient_name && <InputError message={errors.recipient_name} />}
                                </div>

                                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label htmlFor="recipient-email" className="mb-1 block text-sm font-medium">
                                            電子郵件
                                        </label>
                                        <Input
                                            id="recipient-email"
                                            name="recipient-email"
                                            type="email"
                                            value={data.recipient_email}
                                            onChange={(e) => setData('recipient_email', e.target.value)}
                                            placeholder="請輸入電子郵件"
                                            required
                                        />
                                        {errors.recipient_email && <InputError message={errors.recipient_email} />}
                                    </div>
                                    <div>
                                        <label htmlFor="recipient-phone" className="mb-1 block text-sm font-medium">
                                            手機號碼
                                        </label>
                                        <Input
                                            id="recipient-phone"
                                            name="recipient-phone"
                                            value={data.recipient_phone}
                                            onChange={(e) => setData('recipient_phone', e.target.value)}
                                            placeholder="請輸入手機號碼"
                                            required
                                        />
                                        {errors.recipient_phone && <InputError message={errors.recipient_phone} />}
                                    </div>
                                </div>

                                <div>
                                    <label htmlFor="recipient-address" className="mb-1 block text-sm font-medium">
                                        地址
                                    </label>
                                    <Input
                                        id="recipient-address"
                                        name="recipient-address"
                                        value={data.recipient_address}
                                        onChange={(e) => setData('recipient_address', e.target.value)}
                                        placeholder="請輸入詳細地址"
                                        required
                                    />
                                    {errors.recipient_address && <InputError message={errors.recipient_address} />}
                                </div>

                                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label htmlFor="recipient-city" className="mb-1 block text-sm font-medium">
                                            城市
                                        </label>
                                        <Input
                                            id="recipient-city"
                                            name="recipient-city"
                                            value={data.recipient_city}
                                            onChange={(e) => setData('recipient_city', e.target.value)}
                                            placeholder="請輸入城市"
                                            required
                                        />
                                        {errors.recipient_city && <InputError message={errors.recipient_city} />}
                                    </div>
                                    <div>
                                        <label htmlFor="recipient-zip-code" className="mb-1 block text-sm font-medium">
                                            郵遞區號
                                        </label>
                                        <Input
                                            id="recipient-zip-code"
                                            name="recipient-zip-code"
                                            value={data.recipient_zip_code}
                                            onChange={(e) => setData('recipient_zip_code', e.target.value)}
                                            placeholder="請輸入郵遞區號"
                                            required
                                        />
                                        {errors.recipient_zip_code && <InputError message={errors.recipient_zip_code} />}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="mt-6 rounded-xl bg-white p-6 shadow-md">
                            <h2 className="mb-4 text-lg font-semibold">付款方式</h2>

                            <div className="space-y-4">
                                <div>
                                    <label htmlFor="payment-method" className="mb-1 block text-sm font-medium">
                                        選擇付款方式
                                    </label>
                                    <Select value={data.payment_method} onValueChange={(value) => setData('payment_method', value)}>
                                        <SelectTrigger id="payment-method" className="w-full">
                                            <SelectValue placeholder="選擇付款方式" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="cash">現金付款</SelectItem>
                                            <SelectItem value="line_pay">Line Pay</SelectItem>
                                            <SelectItem value="bank_transfer">銀行轉帳</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.payment_method && <InputError message={errors.payment_method} />}
                                </div>
                            </div>
                        </div>

                        <div className="mt-6 rounded-xl bg-white p-6 shadow-md">
                            <h2 className="mb-4 text-lg font-semibold">訂單備註</h2>
                            <div>
                                <label htmlFor="note" className="mb-1 block text-sm font-medium">
                                    備註
                                </label>
                                <Textarea
                                    id="note"
                                    name="note"
                                    value={data.note}
                                    onChange={(e) => setData('note', e.target.value)}
                                    placeholder="備註（選填）"
                                />
                                {errors.note && <InputError message={errors.note} />}
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

                            <Button type="submit" className="mt-6 w-full" disabled={processing}>
                                {processing ? '處理中...' : '提交訂單'}
                            </Button>
                        </div>
                    </div>
                </form>
            </main>
        </AppMainLayout>
    );
}
