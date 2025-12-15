import React, { useEffect, useState } from 'react'
import axios from 'axios'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

export default function Admin() {
  const [labels, setLabels] = useState<string[]>([])
  const [data, setData] = useState<number[]>([])

  useEffect(() => {
    async function load() {
      const res = await axios.get('/api/links/list')
      const links = res.data
      setLabels(links.map((l: any) => l.customSlug))
      setData(links.map((l: any) => l.clickCount))

      const ctx = (document.getElementById('chart') as HTMLCanvasElement | null)
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: { labels: links.map((l: any) => l.customSlug), datasets: [{ label: 'Clicks', data: links.map((l: any) => l.clickCount) }] },
        })
      }
    }
    load()
  }, [])

  return (
    <main style={{ padding: 20 }}>
      <h1>Admin Dashboard</h1>
      <canvas id="chart" style={{ maxWidth: 800 }} />
    </main>
  )
}
