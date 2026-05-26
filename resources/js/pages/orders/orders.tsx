import { Empty } from "antd"
import { Head, usePage } from "@inertiajs/react"
import { SharedData } from "@/types"
import OrderCard from "./order-card"

export default function Orders() {
	const {props} = usePage<SharedData>()

	return (
		<div className="flex flex-grow w-full flex-col gap-4 mt-6">
			<Head title="Мои заказы"/>

			<h1 className="text-2xl font-bold">Мои заказы</h1>

			{!props.orders?.length && (
				<Empty description="Заказов нет." />
			)}

			{props.orders.map(order => <OrderCard order={order} />)}
		</div>
	)
}