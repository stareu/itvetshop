import '../css/app.css'

import AppLayout from './layouts/app-layout'
import ConfigProvider from './config-provider'
import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import ReactDOMServer from 'react-dom/server';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
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
        setup: ({ App, props }) => {
            return <ConfigProvider>
				<App {...props} />
			</ConfigProvider>
        },
    }),
);
