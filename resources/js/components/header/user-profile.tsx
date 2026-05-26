import { Dropdown, Avatar, Button } from 'antd'
import { usePage } from '@inertiajs/react'
import { SharedData } from '@/types'
import { Link } from '@inertiajs/react'
import { UnorderedListOutlined, LogoutOutlined } from '@ant-design/icons'

export default function UserProfile() {
	const {props} = usePage<SharedData>()

	const dropdownItems = [
		{
			key: 1,
			icon: <UnorderedListOutlined />,
			label: <Link href="/orders">Мои заказы</Link>,
		},
	]

	if (props.user.can_logout) {
		dropdownItems.push({
			key: 2,
			icon: <LogoutOutlined />,
			label: (
				<Link
					href="/logout"
					method="post"
					as="div"
				>
					Выйти
				</Link>
			),
		})
	}

	return (
		<Dropdown
			menu={{ items: dropdownItems }}
			trigger={['click']}
		>
			<Button
				type="text"
				style={{ height: 'auto', padding: '6px 10px' }}
			>
				<Avatar src={props.user.avatar}/>

				<div
					style={{
						maxWidth: 80,
						textOverflow: 'ellipsis',
						overflow: 'hidden'
					}}
				>
					{`@${ props.user.name }`}
				</div>
			</Button>
		</Dropdown>
	)
}