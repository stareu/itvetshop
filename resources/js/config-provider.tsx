import { ConfigProvider as AntConfigProvider, theme } from 'antd'
import { createContext, useContext, useState, useEffect } from 'react'

type ContextObject = {
	changeMode: (isLightMode: boolean) => void,
	isLightMode: boolean
}

const ConfigProviderContext = createContext<ContextObject>({
	changeMode: () => {},
	isLightMode: true
})

export default function ConfigProvider({ children }: { children: React.ReactNode }) {
	const isDarkMode = (
		window.matchMedia('(prefers-color-scheme: dark)').matches
		|| localStorage.getItem('isDarkMode') === '1'
	)
	const [isLightMode, setIsLightMode] = useState(!isDarkMode)

	const changeMode = (isLightMode: boolean) => {
		setIsLightMode(isLightMode)
	}

	useEffect(() => {
		document.documentElement.classList.toggle('dark', !isLightMode)
		document.documentElement.style.colorScheme = isLightMode ? 'light' : 'dark'

		if (isLightMode) {
			localStorage.removeItem('isDarkMode')
		}
		else {
			localStorage.setItem('isDarkMode', '1')
		}
	}, [isLightMode])

	return (
		<ConfigProviderContext value={{ changeMode, isLightMode }}>
			<AntConfigProvider
				theme={{
					algorithm: isLightMode ? theme.defaultAlgorithm : theme.darkAlgorithm,
					token: {
						fontFamily: 'Inter'
					}
				}}
			>
				{children}
			</AntConfigProvider>
		</ConfigProviderContext>
	)
}

export const useConfigProviderContext = (): ContextObject => useContext(ConfigProviderContext)