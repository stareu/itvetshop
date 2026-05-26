import { Button, Badge } from "antd"
import { usePage, Link } from "@inertiajs/react"
import { SharedData } from "@/types"
import UserProfile from "./user-profile"
import { HeaderDrawer } from "./header-drawer"
import { ShoppingCartOutlined } from "@ant-design/icons"

export function AppHeader() {
	const {props} = usePage<SharedData>()

	return (
		<div className="py-4 flex justify-between border-b sticky top-0 bg-white dark:bg-[var(--color-background)] z-1">
			<div className="flex justify-between items-center">
				<div className="flex gap-4 items-center">
					<HeaderDrawer />

					<Link href="/" className="text-lg font-bold">{props.name}</Link>
				</div>

			</div>

			<div className="flex gap-4 justify-end items-center">
				<Badge count={props.cart?.totalQuantity}>
					<Link href="/cart">
						<Button
							icon={<ShoppingCartOutlined style={{ fontSize: 22 }}/>}
							size="large"
							style={{ paddingTop: 2, paddingRight: 2 }}
						>
						</Button>
					</Link>
				</Badge>

				{props.user
					? <>
						<UserProfile />
					</>
					: (
						<Link
							href="/login"
						>
							<Button
								type="primary"
							>
								Войти
							</Button>
						</Link>
					)
				}
			</div>
		</div>
	)
}
