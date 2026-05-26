import { Link } from "@inertiajs/react"
import { usePage } from "@inertiajs/react"
import { SharedData } from "@/types"

export default function AppFooter() {
	const {props} = usePage<SharedData>()

	return (
		<footer>
			<div className="mt-5 flex flex-col items-center border-t">
				<div className="mt-4 mb-4 flex space-x-2 text-sm text-gray-500 dark:text-gray-400">
					<div>
						Евгений Старосветский
					</div>
					<div>{` • `}</div>
					<div>{`${new Date().getFullYear()}`}</div>
					<div>{` • `}</div>
					<Link href="/">{props.name}</Link>
				</div>
			</div>
		</footer>
	)
}
