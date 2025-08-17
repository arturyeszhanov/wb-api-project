import axios from "axios";

const API_URL = "/api/orders";
const API_KEY = "E6kUTYrYwZq2tN4QEtyzsbEBk3ie";

// Новый fetchOrders, который возвращает только массив orders и дату
export async function fetchOrders(from, to, limit = 500) {
  try {
    const { data } = await axios.get(API_URL, {
      params: {
        dateFrom: from,
        dateTo: to,
        page: 1,
        limit,
        key: API_KEY,
      },
    });

    // Возвращаем массив и дату каждой записи
    const orders = data.data?.map(item => ({
      ...item,
      date: item.date || item.createdAt || from, // используем поле даты, если есть
    })) || [];

    return orders;
  } catch (err) {
    console.error("Ошибка при загрузке заказов:", err);
    return [];
  }
}
