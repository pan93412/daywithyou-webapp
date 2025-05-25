import { SidebarInset } from '@/components/ui/sidebar';
import * as React from 'react';
import { Toaster } from '@/components/ui/sonner';

interface AppContentProps extends React.ComponentProps<'main'> {
    variant?: 'header' | 'sidebar';
}

export function AppContent({ variant = 'header', children, ...props }: AppContentProps) {
    if (variant === 'sidebar') {
        return <SidebarInset {...props}>{children}</SidebarInset>;
    }

    return (
        <main className="flex min-h-screen flex-col bg-zinc-50">
            {children}
            <Toaster />
        </main>
    );
}
