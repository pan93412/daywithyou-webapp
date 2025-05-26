import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { MessageSquare } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '總覽',
        href: '/dashboard',
    },
];

interface Props {
    commentCount: number;
}

export default function Dashboard({ commentCount }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="總覽" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video rounded-xl border bg-white dark:bg-neutral-900 flex flex-col items-center justify-center gap-2 p-4 text-center shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <MessageSquare className="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-blue-100 dark:text-blue-900 opacity-40 w-40 h-40" />
                        <span className="relative z-10 text-4xl font-bold">{commentCount}</span>
                        <span className="relative z-10 text-sm text-neutral-600 dark:text-neutral-400">總留言數</span>
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}
