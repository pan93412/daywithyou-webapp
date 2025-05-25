import { AppContent } from '@/components/app-content';
import { AppFooter } from '@/components/app-footer';
import { AppHeader } from '@/components/app-header';
import { AppShell } from '@/components/app-shell';
import type { PropsWithChildren } from 'react';

export default function AppMainLayout({ title, children }: PropsWithChildren<{ title: string }>) {
    return (
        <AppShell>
            <AppHeader title={title} />
            <AppContent>{children}</AppContent>
            <AppFooter />
        </AppShell>
    );
}
