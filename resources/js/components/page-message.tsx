import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { AlertTriangle, Info } from 'lucide-react';
import * as React from 'react';

export function PageMessage(props: React.ComponentProps<'div'>) {
    const { message } = usePage<SharedData>().props;

    if (!message) return null;

    switch (message.type) {
        case 'error':
            return (
                <Alert variant="destructive" {...props}>
                    <AlertTriangle className="size-4" />
                    <AlertTitle>{message.title}</AlertTitle>
                    <AlertDescription>{message.content}</AlertDescription>
                </Alert>
            );
        default:
            return (
                <Alert {...props}>
                    <Info className="size-4" />
                    <AlertTitle>{message.title}</AlertTitle>
                    <AlertDescription>{message.content}</AlertDescription>
                </Alert>
            );
    }
}
