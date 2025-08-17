function processChartData(currentData, previousData, metricField = 'total_price') {
    const groupByDate = (data, field) => {
      const map = {}
      data.forEach(item => {
        const date = item.date.split(' ')[0]
        const value = parseFloat(item[field]) || 0
        map[date] = (map[date] || 0) + value
      })
      return map
    }
  
    const currentMap = groupByDate(currentData, metricField)
    const previousMap = groupByDate(previousData, metricField)
  
    const currentDates = Object.keys(currentMap).sort()
    const previousDates = Object.keys(previousMap).sort()
  
    // Простое выравнивание по позиции
    const dateMapping = {}
    const len = Math.min(currentDates.length, previousDates.length)
    for (let i = 0; i < len; i++) {
      dateMapping[currentDates[i]] = previousDates[i]
    }
  
    const currentSeries = currentDates.map(date => currentMap[date] || 0)
    const previousSeries = currentDates.map(date => {
      const prevDate = dateMapping[date]
      return prevDate && previousMap[prevDate] ? previousMap[prevDate] : 0
    })
  
    return {
      labels: currentDates,
      datasets: [
        {
          label: 'Текущий период',
          data: currentSeries,
          borderColor: '#42A5F5',
          backgroundColor: '#42A5F5',
          tension: 0.3,
          fill: false,
        },
        {
          label: 'Прошлый период',
          data: previousSeries,
          borderColor: '#FF6384',
          backgroundColor: '#FF6384',
          tension: 0.3,
          fill: false,
          realDates: currentDates.map(date => dateMapping[date] || '—'),
        },
      ],
    }
  }
  