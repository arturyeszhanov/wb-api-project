export function processTopItems(previousData, currentData, metricField) {
    const sumByNmId = (data) => {
      const map = {};
      data.forEach((item) => {
        const id = item.nm_id;
        const value = parseFloat(item[metricField]) || 0;
        if (!map[id]) {
          map[id] = {
            nm_id: id,
            current: 0,
            previous: 0,
            region: item.oblast || null,
            brand: item.brand || null,
            category: item.subject || null,
            discount: item.discountPercent || 0,
          };
        }
        map[id].current += value;
      });
      return map;
    };
  
    const prevMap = sumByNmId(previousData);
    const currMap = sumByNmId(currentData);
    const allIds = new Set([...Object.keys(prevMap), ...Object.keys(currMap)]);
  
    return Array.from(allIds).map((id) => {
      const prevItem = prevMap[id] || { current: 0, region: null, brand: null, category: null };
      const currItem = currMap[id] || { current: 0, region: null, brand: null, category: null };
  
      const region = currItem.region || prevItem.region;
      const brand = currItem.brand || prevItem.brand;
      const category = currItem.category || prevItem.category;
      const previous = prevItem.current || 0;
      const current = currItem.current || 0;
      const percentChange = previous === 0
        ? current === 0 ? 0 : ((current - previous) / 1) * 100
        : ((current - previous) / previous) * 100;
  
      return {
        nm_id: id,
        previous: parseFloat(previous.toFixed(2)),
        current: parseFloat(current.toFixed(2)),
        percentChange,
        region,
        brand,
        category,
      };
    });
  }
  