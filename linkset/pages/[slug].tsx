import { GetServerSideProps } from 'next'
import prisma from '../lib/prisma'

// Server-side redirect: find link and redirect to originalUrl
export const getServerSideProps: GetServerSideProps = async (context) => {
  const slug = context.params?.slug as string
  const link = await prisma.link.findUnique({ where: { customSlug: slug } })
  if (!link) {
    return { notFound: true }
  }

  // Increment click count
  await prisma.link.update({ where: { customSlug: slug }, data: { clickCount: { increment: 1 } } })

  return {
    redirect: {
      destination: link.originalUrl,
      permanent: false,
    },
  }
}

export default function RedirectPage() {
  return <p>Redirecting…</p>
}
