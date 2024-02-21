<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController {

    //! no longer needed

    // private $projectManager;

    // public function __construct(ProjectManager $projectManager)
    // {
    //     $this->projectManager = $projectManager;
    // }

    //!  new GET api route

    /**
     * @Route("/projects", methods={"GET"})
     */
    public function getProjects(ProjectRepository $projectRepository): JsonResponse {
        $projects = $projectRepository->findAll();
        return $this->json($projects, Response::HTTP_OK);
    }

    /**
     * @Route("/projects", methods={"POST"})
     */
    public function postProject(ProjectRepository $projectRepository, Request $request): JsonResponse
    {
        //! no longer need the project manager with the database setup
        // $project = $this->projectManager->createProject();

        //! replaced with FormType
        // $form = $this->createFormBuilder($project)
        //     ->add('name', TextType::class)
        //     ->add('slug', TextType::class)
        //     ->getForm();

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $data = json_decode($request->getContent(), true);
        $form->handleRequest($request);
        $form->submit($data);

        if ($form->isValid()) {
            //! replaced $project->serialize with repository flush and return of new $project object
            // return $this->json($project->serialize());
            $projectRepository->add($project, true);
            return $this->json($project, Response::HTTP_CREATED);
        }

        return $this->json(["error" => "An error occurred"]);
    }
}
