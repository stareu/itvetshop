import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from 'antd';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

export default function Register() {
    return (
		<div className="flex flex-grow w-full flex-col gap-4 mt-6">
            <Head title="Регистрация" />

            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6 w-full md:w-[400px] mt-8 mx-auto"
            >
                {({ processing, errors }) => (
                    <>
						<h1 className="text-xl font-bold mb-4">Регистрация</h1>

                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="name">Имя</Label>

                                <Input
                                    id="name"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="name"
                                    name="name"
                                />

                                <InputError
                                    message={errors.name}
                                    className="mt-2"
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    tabIndex={2}
                                    autoComplete="email"
                                    name="email"
                                />

                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password">Пароль</Label>

                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    tabIndex={3}
                                    autoComplete="new-password"
                                    name="password"
                                />

                                <InputError message={errors.password} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">
                                    Повторите пароль
                                </Label>

                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    required
                                    tabIndex={4}
                                    autoComplete="new-password"
                                    name="password_confirmation"
                                />

                                <InputError
                                    message={errors.password_confirmation}
                                />
                            </div>

                            <Button
								htmlType="submit"
                                type="primary"
                                className="mt-2 w-full"
                                tabIndex={5}
                                data-test="register-user-button"
                            >
                                {processing && <Spinner />}

                                Создать аккаунт
                            </Button>
                        </div>

                        <div className="text-center text-sm text-muted-foreground">
                            Уже есть аккаунт?{' '}

                            <TextLink href={login()} tabIndex={6}>
                                Войти
                            </TextLink>
                        </div>
                    </>
                )}
            </Form>
		</div>
    );
}
