import '@/utils/telegram-web-app.js'
import { LoadingOutlined } from '@ant-design/icons'
import { useEffect } from 'react'
import { router } from '@inertiajs/react'

export default function Telegram() {
	const postInitData = () => {
		if (window.Telegram.WebApp?.initData) {
			router.post('tg', { initData: window.Telegram.WebApp.initData })
		}
		else {
			setTimeout(postInitData, 300);
		}
	}

	useEffect(() => {
		postInitData()
	}, [])

	return (
		<div className="flex justify-center py-7">
			<LoadingOutlined style={{ fontSize: 40 }}/>
		</div>
	)
}

Telegram.layout = null