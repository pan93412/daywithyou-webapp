import { OrderIndex, PaginatedData } from '@/types/resource';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Calendar, CreditCard, Package, User } from 'lucide-react';
import { type BreadcrumbItem } from '@/types';
import { formatDate } from '@/lib/utils';
import { PageMessage } from '@/components/page-message';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '訂單管理',
        href: '#',
    },
];

interface Props {
    reply: PaginatedData<OrderIndex[]>
}

export default function OrderDashboardPage({reply}: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="訂單管理" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex-1 overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-neutral-900">
                    <h1 className="mb-6 text-2xl font-bold">訂單管理</h1>

                    <PageMessage className="mb-4" />

                    {reply.data.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-12 text-center">
                            <Package className="mb-4 h-12 w-12 text-neutral-400" />
                            <p className="text-lg font-medium">尚無訂單記錄</p>
                            <p className="mt-2 text-sm text-neutral-500">您的訂單將會顯示在這裡</p>
                        </div>
                    ) : (
                        <>
                            <div className="mb-6 overflow-x-auto">
                                <table className="w-full border-collapse">
                                    <thead>
                                        <tr className="border-b border-neutral-200 dark:border-neutral-700">
                                            <th className="whitespace-nowrap py-3 px-4 text-left text-sm font-medium text-neutral-600 dark:text-neutral-300">訂單編號</th>
                                            <th className="whitespace-nowrap py-3 px-4 text-left text-sm font-medium text-neutral-600 dark:text-neutral-300">收件人</th>
                                            <th className="whitespace-nowrap py-3 px-4 text-left text-sm font-medium text-neutral-600 dark:text-neutral-300">付款方式</th>
                                            <th className="whitespace-nowrap py-3 px-4 text-left text-sm font-medium text-neutral-600 dark:text-neutral-300">建立日期</th>
                                            <th className="whitespace-nowrap py-3 px-4 text-left text-sm font-medium text-neutral-600 dark:text-neutral-300">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {reply.data.map(order => (
                                            <tr key={order.id} className="border-b border-neutral-200 hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800">
                                                <td className="whitespace-nowrap py-4 px-4 text-sm">#{order.id}</td>
                                                <td className="whitespace-nowrap py-4 px-4 text-sm">
                                                    <div className="flex items-center">
                                                        <User className="mr-2 h-4 w-4 text-neutral-400" />
                                                        {order.recipient_name}
                                                    </div>
                                                </td>
                                                <td className="whitespace-nowrap py-4 px-4 text-sm">
                                                    <div className="flex items-center">
                                                        <CreditCard className="mr-2 h-4 w-4 text-neutral-400" />
                                                        {getPaymentMethodText(order.payment_method)}
                                                    </div>
                                                </td>
                                                <td className="whitespace-nowrap py-4 px-4 text-sm">
                                                    <div className="flex items-center">
                                                        <Calendar className="mr-2 h-4 w-4 text-neutral-400" />
                                                        {formatDate(new Date(order.created_at))}
                                                    </div>
                                                </td>
                                                <td className="whitespace-nowrap py-4 px-4 text-sm">
                                                    <Link href={`/dashboard/orders/${order.id}`}>
                                                        <Button variant="outline" size="sm">查看詳情</Button>
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination Controls */}
                            {reply.meta.last_page > 1 && (
                                <div className="mt-8 flex items-center justify-center gap-2">
                                    {reply.meta.links.map((link, index) =>
                                        link.url ? (
                                            <Link key={index} href={link.url}>
                                                <Button variant={link.active ? 'default' : 'outline'} size="sm" className="min-w-[2.5rem]">
                                                    {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                                                </Button>
                                            </Link>
                                        ) : (
                                            <span key={index} className="px-2 text-gray-400">
                                                {link.label.replace(/&laquo;|&raquo;/g, '')}
                                            </span>
                                        ),
                                    )}
                                </div>
                            )}

                            <div className="mt-4 text-center text-sm text-gray-500">
                                顯示 {reply.meta.from} 至 {reply.meta.to} 筆，共 {reply.meta.total} 筆訂單
                            </div>
                        </>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}

function getPaymentMethodText(method: string): string {
    switch (method) {
        case 'cash':
            return '貨到付款';
        case 'line_pay':
            return 'LINE Pay';
        case 'bank_transfer':
            return '銀行轉帳';
        default:
            return method;
    }
}
