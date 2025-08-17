export function processItems(previousData, currentData, metricField, options = { aggregate: true }) {
  const sumData = (data, aggregate, isPrevious = false) => {
    const map = {};
    data.forEach((item) => {
      const id = item.nm_id;

      let value = 0;

      if (metricField === "is_cancel") {
        value = item.is_cancel ? 1 : 0;
      } else if (metricField === "income_id") {
        value = item.income_id || null;
      } else {
        const v = item[metricField];
        if (typeof v === "boolean") value = v ? 1 : 0;
        else if (typeof v === "string") value = v === "true" ? 1 : parseFloat(v) || 0;
        else value = parseFloat(v) || 0;
      }

      const key = aggregate ? id : `${id}_${item.oblast}_${item.brand}_${item.subject}`;

      if (!map[key]) {
        map[key] = {
          nm_id: id,
          current: metricField === "income_id" ? new Set() : 0,
          previous: metricField === "income_id" ? new Set() : 0,
          countCurrent: 0,
          countPrevious: 0,
          region: item.oblast || null,
          brand: item.brand || null,
          category: item.subject || null,
        };
      }

      // Для income_id используем множества
      if (metricField === "income_id") {
        if (value) {
          if (isPrevious) map[key].previous.add(value);
          else map[key].current.add(value);
        }
      } else {
        if (isPrevious) {
          map[key].previous += value;
          map[key].countPrevious += 1;
        } else {
          map[key].current += value;
          map[key].countCurrent += 1;
        }
      }
    });
    return map;
  };

  const prevMap = sumData(previousData, options.aggregate, true);
  const currMap = sumData(currentData, options.aggregate, false);

  const allKeys = new Set([...Object.keys(prevMap), ...Object.keys(currMap)]);

  return Array.from(allKeys).map((key) => {
    const prevItem = prevMap[key] || {};
    const currItem = currMap[key] || {};

    let previous, current;

    if (metricField === "income_id") {
      previous = prevItem.previous ? prevItem.previous.size : 0;
      current = currItem.current ? currItem.current.size : 0;
    } else if (metricField === "discount_percent") {
      previous = prevItem.countPrevious ? prevItem.previous / prevItem.countPrevious : 0;
      current = currItem.countCurrent ? currItem.current / currItem.countCurrent : 0;
    } else {
      previous = prevItem.previous || 0;
      current = currItem.current || 0;
    }

    const percentChange = previous === 0
      ? current === 0 ? 0 : 100
      : ((current - previous) / previous) * 100;

    return {
      nm_id: currItem.nm_id || prevItem.nm_id || null,
      previous: parseFloat(previous.toFixed(2)),
      current: parseFloat(current.toFixed(2)),
      percentChange,
      region: currItem.region || prevItem.region || null,
      brand: currItem.brand || prevItem.brand || null,
      category: currItem.category || prevItem.category || null,
    };
  });
}
