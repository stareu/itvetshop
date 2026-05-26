import { SunOutlined, MoonOutlined } from '@ant-design/icons'
import { useConfigProviderContext } from '@/config-provider'

export default function ModeSwitcher() {
	const {changeMode, isLightMode} = useConfigProviderContext()

	if (isLightMode) {
		return (
			<SunOutlined
				style={{ fontSize: 21 }}
				onClick={() => changeMode(!isLightMode)}
			/>
		)
	}

	return (
		<MoonOutlined
			style={{ fontSize: 21 }}
			onClick={() => changeMode(!isLightMode)}
		/>
	)
}