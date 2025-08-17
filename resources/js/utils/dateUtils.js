export function formatDate(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, "0");
    const dd = String(date.getDate()).padStart(2, "0");
    return `${yyyy}-${mm}-${dd}`;
  }
  
  export function buildDateRange(selectedDates) {
    if (!selectedDates || selectedDates.length < 2) return null;
  
    const [start, end] = selectedDates;
    if (!(start instanceof Date) || isNaN(start) || !(end instanceof Date) || isNaN(end)) {
      return null;
    }
  
    const durationMs = end.getTime() - start.getTime();
    const prevStart = new Date(start.getTime() - durationMs);
    const prevEnd = new Date(end.getTime() - durationMs);
  
    return {
      current: { from: formatDate(start), to: formatDate(end) },
      previous: { from: formatDate(prevStart), to: formatDate(prevEnd) },
    };
  }
  