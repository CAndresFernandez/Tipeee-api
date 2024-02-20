<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\ProjectManager;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController {

    private $projectManager;

    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }

    /**
     * @Route("/projects", methods={"POST"})
     */
    public function postProject(SerializerInterface $serializer, Request $request): JsonResponse
    {
        $project = $this->projectManager->createProject();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $serializer->serialize($project, 'json');
        }

        return $this->json(["error" => "An error occurred"]);
    }
}
