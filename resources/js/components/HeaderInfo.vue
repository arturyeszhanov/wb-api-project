<template>
    <header
        class="w-[90%] mx-auto bg-gradient-to-r from-pink-500 to-purple-700 shadow-xl mt-8"
    >
        <div
            class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center"
        >
            <router-link
                to="/"
                class="text-3xl font-extrabold text-white hover:text-amber-300 transition-colors duration-200"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="3.8em"
                    height="3em"
                    viewBox="0 0 24 24"
                >
                    <!-- Icon from Prime Icons by PrimeTek - https://github.com/primefaces/primeicons/blob/master/LICENSE -->
                    <path
                        fill="currentColor"
                        d="M4.5 20.25a.76.76 0 0 1-.75-.75v-15a.75.75 0 0 1 1.5 0v15a.76.76 0 0 1-.75.75"
                    />
                    <path
                        fill="currentColor"
                        d="M19.5 20.25h-15a.75.75 0 0 1 0-1.5h15a.75.75 0 0 1 0 1.5m-5.5-5.5a.74.74 0 0 1-.53-.22L11 12.06l-2.47 2.47a.75.75 0 0 1-1.06-1.06l3-3a.75.75 0 0 1 1.06 0L14 12.94l3.47-3.47a.75.75 0 0 1 1.06 1.06l-4 4a.74.74 0 0 1-.53.22"
                    />
                    <path
                        fill="currentColor"
                        d="M18.5 13.84a.76.76 0 0 1-.75-.75v-2.84H15a.75.75 0 0 1 0-1.5h3.5a.76.76 0 0 1 .75.75v3.59a.76.76 0 0 1-.75.75"
                    />
                </svg>
                WB API
            </router-link>
            <div class="container mx-auto px-2 py-2">
                <div
                    class="w-[70%] grid grid-cols-1 md:grid-cols-2 gap-8 ms-auto"
                >
                    <!-- Колонка 1 -->
                    <div class="space-y-4">
                        <div>
                            <h2
                                class="text-2xs text-white font-semibold mb-2 font-roboto"
                            >
                                Как пользоваться графиками
                            </h2>
                            <p
                                class="text-gray-700 text-xs text-white font-roboto"
                            >
                                Под каждым графиком есть таблица с товарами,
                                которые показали наибольшие изменения по
                                сравнению с предыдущим периодом. Вы можете
                                кликнуть на график, чтобы перейти на страницу с
                                подробной статистикой по этому показателю.
                            </p>
                        </div>
                    </div>

                    <!-- Колонка 2 -->
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-2xs text-white font-semibold mb-2">
                                Работа с фильтрами
                            </h2>
                            <p class="text-gray-700 text-xs text-white">
                                Слева вверху расположены фильтры по артикулу,
                                региону, дате, категории и бренду. Все выбранные
                                фильтры сохраняются при переходах между
                                страницами, что удобно для сравнения данных.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
