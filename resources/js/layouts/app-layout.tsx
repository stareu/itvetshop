import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';
import AppFooter from '@/components/app-footer';
import { AppHeader } from '@/components/header/app-header';
import { Head } from '@inertiajs/react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children }: AppLayoutProps) {
	return (
		<div className="flex min-h-screen w-full flex-col">
			<main className="mx-auto px-4 flex h-full w-full max-w-7xl flex-1 flex-col gap-4">
				<AppHeader />

				{children}

				<AppFooter />
			</main>
		</div>
	)
}
