import { Drawer, Button } from "antd"
import { MenuOutlined, FileTextOutlined, UnorderedListOutlined, UserOutlined} from '@ant-design/icons'
import { useState } from "react"
import { usePage, Link } from '@inertiajs/react'
import { SharedData } from '@/types'
import ModeSwitcher from "./mode-switcher"

export function HeaderDrawer() {
	const [open, setIsOpen] = useState(false)
	const {props} = usePage<SharedData>()

	return (
		<>
			<Button
				icon={<MenuOutlined style={{ fontSize: 18 }}/>}
				onClick={() => setIsOpen(true)}
				size="large"
				style={{ paddingTop: 2, flexShrink: 0 }}
			></Button>

			<Drawer
				title={
					<div className="flex justify-between">
						Меню

						<ModeSwitcher />
					</div>
				}
				placement="left"
				open={open}
				mask={{ blur: false }}
				onClose={() => setIsOpen(false)}
			>
				<div className="flex flex-col justify-between h-full">
					<div className="flex flex-col gap-3">
						<div className="text-base">
							{props.user?.is_admin && (
								<Button
									href="/admin"
									type="link"
									icon={<UserOutlined />}
									style={{ width: '100%', justifyContent: 'flex-start' }}
								>
									Админка
								</Button>
							)}
						</div>

						<div className="text-base">
							{props.user && (
								<Link href="/orders">
									<Button
										type="link"
										icon={<UnorderedListOutlined />}
										style={{ width: '100%', justifyContent: 'flex-start' }}
										onClick={() => setIsOpen(false)}
									>
										Мои заказы
									</Button>
								</Link>
							)}
						</div>

						<div className="text-base">
							<Link href="/privacy">
								<Button
									type="link"
									icon={<FileTextOutlined />}
									style={{ width: '100%', justifyContent: 'flex-start' }}
									onClick={() => setIsOpen(false)}
								>
									Конфиденциальность
								</Button>
							</Link>
						</div>

						<div className="text-base">
							<Link href="/offer">
								<Button
									href="/offer"
									type="link"
									icon={<FileTextOutlined />}
									style={{ width: '100%', justifyContent: 'flex-start' }}
									onClick={() => setIsOpen(false)}
								>
									Оферта
								</Button>
							</Link>
						</div>
					</div>

					<div className="color-[#0e1d27] text-sm flex flex-col gap-3">
						<div>ИП Семенов Виталий Сергеевич</div>
						<div>ИНН: 100130650588</div>
						<div>ОРГН ИП 324100000005633</div>
						<div>Юридический адрес: Россия, 185014, Республика Карелия, г. Петрозаводск, Древлянка 5, корп. 2,  кв. 28</div>
						<div>+7 902 771 26 56</div>
						<div>vitaliy_10rus@vk.com</div>
					</div>
				</div>
			</Drawer>
		</>
	)
}
