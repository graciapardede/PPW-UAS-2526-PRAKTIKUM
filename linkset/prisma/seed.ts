import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  // create sample user
  const user = await prisma.user.upsert({
    where: { email: 'demo@local' },
    update: {},
    create: { email: 'demo@local', name: 'Demo User' },
  })

  await prisma.link.createMany({
    data: [
      { originalUrl: 'https://example.com/1', customSlug: 'example-1', userId: user.id },
      { originalUrl: 'https://example.com/2', customSlug: 'example-2', userId: user.id },
    ],
    skipDuplicates: true,
  })
}

main()
  .catch((e) => { console.error(e); process.exit(1) })
  .finally(async () => { await prisma.$disconnect() })
