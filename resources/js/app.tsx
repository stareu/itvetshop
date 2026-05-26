import '../css/app.css'

import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { initializeTheme } from './hooks/use-appearance'
import AppLayout from './layouts/app-layout'
import ConfigProvider from './config-provider'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
    title: (title) => (title ? `${appName} | ${title}` : appName),
	resolve: (name) => (
		resolvePageComponent(
			`./pages/${name}.tsx`,
			import.meta.glob('./pages/**/*.tsx'),
		).then((page: any) => {
			page.default.layout = 'layout' in page.default
				? page.default.layout
				: ((page: React.ReactNode) => <AppLayout>{page}</AppLayout>)

			return page
		})
	),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <StrictMode>
				<ConfigProvider>
                	<App {...props} />
				</ConfigProvider>
            </StrictMode>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
